<?php
/**
 * Hospital Integration Service
 * Handles referral payloads and transmission to partner hospitals / RS
 */
class HospitalIntegrationService
{
    private $config;
    private $db;
    private $logger;
    private $logFile;

    public function __construct($db = null, $logger = null)
    {
        require_once __DIR__ . '/../Database.php';
        require_once __DIR__ . '/../Security/AuditLogger.php';
        require_once __DIR__ . '/../Security/DataAnonymizer.php';

        $this->db = $db ?: Database::getInstance()->getConnection();
        $this->logger = $logger ?: new AuditLogger($this->db);
        $this->config = require dirname(__DIR__, 2) . '/config/hospital.php';
        $this->logFile = dirname(__DIR__, 2) . '/logs/hospital_integrations.log';

        if (!is_dir(dirname($this->logFile))) {
            mkdir(dirname($this->logFile), 0750, true);
        }
    }

    public function isEnabled()
    {
        $hasFacilities = !empty($this->config['facilities']);
        return ($this->config['enabled'] ?? false) && $hasFacilities;
    }

    public function getActiveFacilities()
    {
        return array_values(array_filter($this->config['facilities'], function ($facility) {
            return $facility['active'] ?? true;
        }));
    }

    public function findFacility($id)
    {
        foreach ($this->config['facilities'] as $facility) {
            if ($facility['id'] === $id) {
                return $facility;
            }
        }
        return null;
    }

    /**
     * Send assessment payload to hospital partner
     * @param array $payload
     * @return array
     */
    public function sendAssessment(array $payload)
    {
        if (!$this->isEnabled()) {
            return [
                'success' => false,
                'status' => 'DISABLED',
                'message' => 'Integrasi rumah sakit sedang non-aktif'
            ];
        }

        $facility = $this->findFacility($payload['hospital_id'] ?? '');
        if (!$facility) {
            throw new InvalidArgumentException('Rumah sakit mitra tidak ditemukan.');
        }

        $requestBody = $this->buildRequestBody($facility, $payload);
        $recordId = $this->createIntegrationRecord($payload, $facility, $requestBody);

        try {
            $response = $this->transmit($facility, $requestBody);
            $this->finalizeRecord($recordId, $response);

            $statusLabel = $response['status'] ?? 'PENDING';
            $statusSuccess = in_array($statusLabel, ['SUCCESS', 'QUEUED'], true);

            $this->logger->log(
                AuditLogger::EVENT_HOSPITAL_SYNC,
                $payload['user_id'] ?? null,
                'Sinkronisasi hasil assessment ke rumah sakit',
                [
                    'hospital_id' => $facility['id'],
                    'hospital_name' => $facility['name'],
                    'status' => $statusLabel
                ],
                $statusSuccess ? 'SUCCESS' : 'FAILURE'
            );

            return [
                'success' => $statusSuccess,
                'status' => $statusLabel,
                'message' => $response['message'] ?? ($statusSuccess ? 'Data berhasil dikirim.' : 'Pengiriman gagal'),
                'reference' => $requestBody['patient_reference'],
                'facility' => [
                    'id' => $facility['id'],
                    'name' => $facility['name']
                ]
            ];
        } catch (Throwable $th) {
            $this->finalizeRecord($recordId, [
                'status' => 'FAILED',
                'message' => $th->getMessage(),
                'code' => null,
                'body' => null,
                'error' => $th->getMessage()
            ]);

            $this->logger->log(
                AuditLogger::EVENT_HOSPITAL_SYNC,
                $payload['user_id'] ?? null,
                'Sinkronisasi assessment ke RS gagal',
                [
                    'hospital_id' => $facility['id'],
                    'error' => $th->getMessage()
                ],
                'FAILURE'
            );

            return [
                'success' => false,
                'status' => 'FAILED',
                'message' => $th->getMessage()
            ];
        }
    }

    private function buildRequestBody(array $facility, array $payload)
    {
        $input = $payload['input'] ?? [];
        $result = $payload['result'] ?? [];
        $patientReference = $payload['patient_reference'] ?: $this->generatePatientReference($payload);

        return [
            'facility_id' => $facility['id'],
            'facility_name' => $facility['name'],
            'assessment_id' => $payload['assessment_id'],
            'patient_reference' => $patientReference,
            'submitted_at' => date(DATE_ATOM),
            'risk_category' => $result['prediction'] ?? 'Unknown',
            'risk_score' => $result['risk_score'] ?? null,
            'confidence' => $result['confidence'] ?? null,
            'probabilities' => $result['probabilities'] ?? [],
            'recommendations' => $result['recommendations'] ?? [],
            'notes' => $payload['notes'] ?? null,
            'features' => [
                'age' => (float)($input['age'] ?? 0),
                'stress' => (float)($input['stress'] ?? 0),
                'anxiety' => (float)($input['anxiety'] ?? 0),
                'depression' => (float)($input['depression'] ?? 0),
                'sleep' => (float)($input['sleep'] ?? 0),
                'exercise' => $input['exercise'] ?? 'Unknown',
                'social_support' => $input['social_support'] ?? 'Unknown',
                'mental_history' => $input['mental_history'] ?? 'Unknown'
            ],
            'meta' => [
                'source' => 'mental_health_predictor_laravel',
                'mode' => $this->config['mode'] ?? 'sandbox',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
            ]
        ];
    }

    private function generatePatientReference($payload)
    {
        $seed = ($payload['user_id'] ?? 'anon') . '-' . ($payload['assessment_id'] ?? time());
        return 'RS-' . strtoupper(substr(hash('sha1', $seed), 0, 10));
    }

    private function createIntegrationRecord(array $payload, array $facility, array $requestBody)
    {
        $stmt = $this->db->prepare("INSERT INTO hospital_integrations 
            (assessment_id, user_id, hospital_id, hospital_name, patient_reference, status, request_payload, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, 'PENDING', ?, NOW(), NOW())");
        $stmt->execute([
            $payload['assessment_id'],
            $payload['user_id'] ?? null,
            $facility['id'],
            $facility['name'],
            $requestBody['patient_reference'],
            json_encode($requestBody)
        ]);

        $this->writeLog(sprintf('Queued integration #%s -> %s', $this->db->lastInsertId(), $facility['name']));
        return $this->db->lastInsertId();
    }

    private function finalizeRecord($id, array $response)
    {
        $stmt = $this->db->prepare("UPDATE hospital_integrations 
            SET status = ?, response_code = ?, response_payload = ?, error_message = ?, updated_at = NOW()
            WHERE id = ?");
        $stmt->execute([
            $response['status'] ?? 'FAILED',
            $response['code'] ?? null,
            !empty($response['body']) ? json_encode($response['body']) : null,
            $response['error'] ?? null,
            $id
        ]);

        $this->writeLog(sprintf('Integration %s status -> %s', $id, $response['status'] ?? 'FAILED'));
    }

    private function transmit(array $facility, array $payload)
    {
        $endpoint = $facility['endpoint'] ?? '';
        if (empty($endpoint)) {
            return [
                'status' => 'QUEUED',
                'message' => 'Endpoint belum dikonfigurasi. Data disimpan untuk pengiriman manual.',
                'code' => null,
                'body' => $payload
            ];
        }

        if (!function_exists('curl_init')) {
            return [
                'status' => 'QUEUED',
                'message' => 'cURL tidak tersedia. Simpan payload secara lokal.',
                'code' => null,
                'body' => $payload
            ];
        }

        $attempts = max(1, (int)($this->config['max_retries'] ?? 1));
        $timeout = max(5, (int)($this->config['timeout'] ?? 8));
        $lastError = null;

        for ($i = 0; $i < $attempts; $i++) {
            $ch = curl_init($endpoint);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->buildHeaders($facility));
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

            if (($facility['auth']['type'] ?? '') === 'basic') {
                $username = $facility['auth']['username'] ?? '';
                $password = $facility['auth']['password'] ?? '';
                curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
            }

            $responseBody = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if (curl_errno($ch)) {
                $lastError = curl_error($ch);
                curl_close($ch);
                continue;
            }

            curl_close($ch);

            $decoded = json_decode($responseBody, true);
            if ($httpCode >= 200 && $httpCode < 300) {
                return [
                    'status' => 'SUCCESS',
                    'message' => $decoded['message'] ?? 'Payload diterima rumah sakit.',
                    'code' => $httpCode,
                    'body' => $decoded ?? $responseBody
                ];
            }

            $lastError = $decoded['error'] ?? ('HTTP ' . $httpCode);
        }

        return [
            'status' => 'FAILED',
            'message' => $lastError ?: 'Pengiriman gagal',
            'code' => null,
            'body' => null,
            'error' => $lastError
        ];
    }

    private function buildHeaders(array $facility)
    {
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
            'X-Integration-Source: mental-health-predictor'
        ];

        if (($facility['auth']['type'] ?? '') === 'token' && !empty($facility['auth']['token'])) {
            $headers[] = 'Authorization: Bearer ' . $facility['auth']['token'];
        }

        return $headers;
    }

    private function writeLog($message)
    {
        $entry = sprintf('[%s] %s%s', date('Y-m-d H:i:s'), $message, PHP_EOL);
        file_put_contents($this->logFile, $entry, FILE_APPEND | LOCK_EX);
    }
}
