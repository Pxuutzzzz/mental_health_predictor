<?php
namespace Controllers;

require_once __DIR__ . '/../Database.php';
require_once __DIR__ . '/../Security/AuditLogger.php';

/**
 * Screening Tools Controller
 * Handles PHQ-9 and GAD-7 screening assessments
 */
class ScreeningController
{
    private $db;
    private $logger;
    
    public function __construct()
    {
        $this->db = \Database::getInstance();
        $this->logger = new \AuditLogger($this->db->getConnection());
    }
    
    /**
     * Save screening result (PHQ-9 or GAD-7)
     */
    public function saveScreening()
    {
        header('Content-Type: application/json');
        
        try {
            $screening_type = $_POST['screening_type'] ?? '';
            
            if (!in_array($screening_type, ['PHQ9', 'GAD7', 'COMBINED'])) {
                throw new \Exception('Invalid screening type');
            }
            
            // Get patient info
            $patient_name = $_POST['patient_name'] ?? null;
            $patient_mrn = $_POST['patient_mrn'] ?? null;
            $clinician_name = $_POST['clinician_name'] ?? null;
            $clinician_role = $_POST['clinician_role'] ?? null;
            $clinical_notes = $_POST['clinical_notes'] ?? null;
            
            // Validate required fields
            if (empty($patient_name) || empty($clinician_name) || empty($clinician_role)) {
                throw new \Exception('Missing required fields');
            }
            
            // Get user_id from session if available
            $user_id = $_SESSION['user_id'] ?? null;
            
            // Prepare data based on screening type
            $data = [
                'user_id' => $user_id,
                'patient_name' => $patient_name,
                'patient_mrn' => $patient_mrn,
                'screening_type' => $screening_type,
                'clinician_name' => $clinician_name,
                'clinician_role' => $clinician_role,
                'clinical_notes' => $clinical_notes,
                'assessment_date' => date('Y-m-d'),
                'urgent_flag' => intval($_POST['urgent_flag'] ?? 0)
            ];
            
            // PHQ-9 data
            if ($screening_type === 'PHQ9' || $screening_type === 'COMBINED') {
                for ($i = 1; $i <= 9; $i++) {
                    $data["phq9_q$i"] = intval($_POST["phq9_q$i"] ?? 0);
                }
                $data['phq9_total_score'] = intval($_POST['phq9_total_score'] ?? 0);
                $data['phq9_severity'] = $_POST['phq9_severity'] ?? null;
            }
            
            // GAD-7 data
            if ($screening_type === 'GAD7' || $screening_type === 'COMBINED') {
                for ($i = 1; $i <= 7; $i++) {
                    $data["gad7_q$i"] = intval($_POST["gad7_q$i"] ?? 0);
                }
                $data['gad7_total_score'] = intval($_POST['gad7_total_score'] ?? 0);
                $data['gad7_severity'] = $_POST['gad7_severity'] ?? null;
            }
            
            // Generate recommendations
            $recommendations = $this->generateRecommendations($data, $screening_type);
            $data['recommendations'] = $recommendations;
            
            // Check if referral needed
            $data['referral_needed'] = $this->checkReferralNeeded($data, $screening_type);
            
            // Build SQL
            $columns = array_keys($data);
            $placeholders = array_map(function($col) { return ":$col"; }, $columns);
            
            $sql = "INSERT INTO screening_results (" . 
                   implode(', ', $columns) . 
                   ") VALUES (" . 
                   implode(', ', $placeholders) . 
                   ")";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            
            // Bind parameters
            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            
            $stmt->execute();
            $screening_id = $this->db->getConnection()->lastInsertId();
            
            // Log to audit trail
            $this->logger->log(
                'screening_submitted',
                'screening_results',
                $screening_id,
                [
                    'screening_type' => $screening_type,
                    'patient_name' => $patient_name,
                    'patient_mrn' => $patient_mrn,
                    'clinician' => $clinician_name,
                    'urgent' => $data['urgent_flag']
                ]
            );
            
            echo json_encode([
                'success' => true,
                'id' => $screening_id,
                'message' => 'Screening saved successfully'
            ]);
            
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Get screening history
     */
    public function getHistory()
    {
        try {
            $user_id = $_SESSION['user_id'] ?? null;
            $patient_mrn = $_GET['mrn'] ?? null;
            
            $sql = "SELECT * FROM screening_results WHERE 1=1";
            $params = [];
            
            if ($user_id) {
                $sql .= " AND user_id = :user_id";
                $params[':user_id'] = $user_id;
            }
            
            if ($patient_mrn) {
                $sql .= " AND patient_mrn = :mrn";
                $params[':mrn'] = $patient_mrn;
            }
            
            $sql .= " ORDER BY created_at DESC LIMIT 50";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute($params);
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'data' => $results
            ]);
            
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Get screening detail by ID
     */
    public function getScreening($id)
    {
        try {
            $sql = "SELECT * FROM screening_results WHERE id = :id";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$result) {
                throw new \Exception('Screening not found');
            }
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'data' => $result
            ]);
            
        } catch (\Exception $e) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Generate recommendations based on scores
     */
    private function generateRecommendations($data, $type)
    {
        $recommendations = [];
        
        // PHQ-9 recommendations
        if (isset($data['phq9_total_score'])) {
            $score = $data['phq9_total_score'];
            
            if ($score >= 20) {
                $recommendations[] = 'URGENT: Severe depression - immediate psychiatric referral required';
                $recommendations[] = 'Consider hospitalization if suicide risk present';
            } elseif ($score >= 15) {
                $recommendations[] = 'Moderately severe depression - active treatment required';
                $recommendations[] = 'Refer to psychiatrist for medication and therapy';
            } elseif ($score >= 10) {
                $recommendations[] = 'Moderate depression - consider therapy or medication';
                $recommendations[] = 'Refer to mental health specialist';
            } elseif ($score >= 5) {
                $recommendations[] = 'Mild depression - watchful waiting with support';
                $recommendations[] = 'Consider counseling';
            }
            
            // Check suicide risk (Q9)
            if (isset($data['phq9_q9']) && $data['phq9_q9'] > 0) {
                array_unshift($recommendations, 'ALERT: Suicide ideation detected - assess risk immediately');
            }
        }
        
        // GAD-7 recommendations
        if (isset($data['gad7_total_score'])) {
            $score = $data['gad7_total_score'];
            
            if ($score >= 15) {
                $recommendations[] = 'Severe anxiety - immediate treatment required';
                $recommendations[] = 'Refer to psychiatrist for medication evaluation';
                $recommendations[] = 'Consider CBT therapy';
            } elseif ($score >= 10) {
                $recommendations[] = 'Moderate anxiety - active intervention needed';
                $recommendations[] = 'Refer to psychologist or psychiatrist';
            } elseif ($score >= 5) {
                $recommendations[] = 'Mild anxiety - monitor and provide support';
                $recommendations[] = 'Teach relaxation techniques';
            }
        }
        
        return implode('; ', $recommendations);
    }
    
    /**
     * Check if referral is needed
     */
    private function checkReferralNeeded($data, $type)
    {
        // PHQ-9: score >= 10 needs referral
        if (isset($data['phq9_total_score']) && $data['phq9_total_score'] >= 10) {
            return true;
        }
        
        // GAD-7: score >= 10 needs referral
        if (isset($data['gad7_total_score']) && $data['gad7_total_score'] >= 10) {
            return true;
        }
        
        // Suicide risk always needs referral
        if (isset($data['phq9_q9']) && $data['phq9_q9'] > 0) {
            return true;
        }
        
        return false;
    }
}
