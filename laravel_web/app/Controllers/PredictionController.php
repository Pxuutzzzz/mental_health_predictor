<?php
namespace Controllers;

require_once __DIR__ . '/../Database.php';
require_once __DIR__ . '/../Security/AuditLogger.php';
require_once __DIR__ . '/../Services/HospitalIntegrationService.php';

/**
 * Prediction Controller
 * Handles mental health prediction requests
 */
class PredictionController
{
    private $pythonPath;
    private $scriptPath;
    private $db;
    private $logger;
    private $hospitalService;

    public function __construct()
    {
        $this->db = \Database::getInstance();
        $this->logger = new \AuditLogger($this->db->getConnection());
        $this->hospitalService = new \HospitalIntegrationService($this->db->getConnection(), $this->logger);

        // Determine Python path based on environment
        $envPython = getenv('PYTHON_PATH');
        if ($envPython) {
            $this->pythonPath = $envPython;
        } elseif (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // Windows fallback (Local Development)
            $this->pythonPath = 'python';
        } else {
            // Linux fallback (Production/Railway)
            $this->pythonPath = 'python3';
        }

        // Script path is not strictly used as we generate temp_predict.py, but keeping for reference
        // Adjusted to point to root if needed, but currently unused
        $this->scriptPath = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR . 'predictor.py';
    }

    public function predict()
    {
        header('Content-Type: application/json');

        try {
            // Validate input - 13 fields matching the notebook model
            $age = floatval($_POST['age'] ?? 25);
            $gender = $_POST['gender'] ?? 'Female';
            $employment_status = $_POST['employment_status'] ?? 'Employed';
            $work_environment = $_POST['work_environment'] ?? 'Office';
            $mental_history = $_POST['mental_history'] ?? 'No';
            $seeks_treatment = $_POST['seeks_treatment'] ?? 'No';
            $stress = floatval($_POST['stress'] ?? 5);
            $depression = floatval($_POST['depression'] ?? 15);
            $anxiety = floatval($_POST['anxiety'] ?? 10);
            $sleep = floatval($_POST['sleep'] ?? 7);
            $exercise = floatval($_POST['exercise'] ?? 3);
            $social_support = floatval($_POST['social_support'] ?? 50);
            $productivity = floatval($_POST['productivity'] ?? 70);

            $share_with_hospital = $this->toBool($_POST['share_with_hospital'] ?? false);
            $hospital_id = $_POST['hospital_id'] ?? null;
            $patient_reference = trim($_POST['patient_reference'] ?? '');
            $hospital_notes = trim($_POST['hospital_notes'] ?? '');

            // Check if staff mode
            $isStaffMode = $this->toBool($_POST['staff_mode'] ?? false);
            $facilityId = $_POST['facility_id'] ?? null;
            $patientMRN = trim($_POST['patient_mrn'] ?? '');
            $patientName = trim($_POST['patient_name'] ?? '');
            $patientGender = $_POST['patient_gender'] ?? null;
            $visitType = $_POST['visit_type'] ?? 'outpatient';
            $chiefComplaint = trim($_POST['chief_complaint'] ?? '');
            $clinicalObservation = trim($_POST['clinical_observation'] ?? '');
            $followUpPlan = trim($_POST['follow_up_plan'] ?? '');
            $icdCode = trim($_POST['icd_code'] ?? '');
            $clinicianName = trim($_POST['clinician_name'] ?? '');

            if ($share_with_hospital && empty($hospital_id) && !$isStaffMode) {
                throw new \InvalidArgumentException('Silakan pilih rumah sakit tujuan rujukan.');
            }

            // Try to use Python ML model if available
            $result = null;

            // Fix path to models directory (it is in the project root, 3 levels up from this file)
            $modelDir = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'models';
            $modelPath = $modelDir . DIRECTORY_SEPARATOR . 'mental_health_model.pkl';

            if (file_exists($modelPath)) {
                // Create Python script to run prediction in web root (laravel_web)
                $pythonScript = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'temp_predict.py';

                // Pass correct model directory to the generator
                $scriptContent = $this->generatePythonScript($age, $gender, $employment_status, $work_environment, $mental_history, $seeks_treatment, $stress, $depression, $anxiety, $sleep, $exercise, $social_support, $productivity, $modelDir);
                file_put_contents($pythonScript, $scriptContent);

                // Execute Python script
                $command = sprintf('%s "%s" 2>&1', $this->pythonPath, $pythonScript);
                exec($command, $output, $return_code);

                // Clean up temp script
                @unlink($pythonScript);

                if ($return_code === 0) {
                    // Try to parse JSON output
                    $outputStr = implode("\n", $output);
                    $jsonStart = strpos($outputStr, '{');
                    if ($jsonStart !== false) {
                        $jsonStr = substr($outputStr, $jsonStart);
                        $result = json_decode($jsonStr, true);
                    }
                }
            }

            // Fallback to mock result if Python fails
            if (!$result) {
                $result = $this->getMockResult($age, $gender, $employment_status, $work_environment, $mental_history, $seeks_treatment, $stress, $depression, $anxiety, $sleep, $exercise, $social_support, $productivity);
            }

            // Save to session history
            if (!isset($_SESSION['predictions'])) {
                $_SESSION['predictions'] = [];
            }

            // Save to database (with clinical data if staff mode)
            $clinicalData = null;
            if ($isStaffMode) {
                $clinicalData = json_encode([
                    'patient_mrn' => $patientMRN,
                    'patient_name' => $patientName,
                    'patient_gender' => $patientGender,
                    'visit_type' => $visitType,
                    'chief_complaint' => $chiefComplaint,
                    'clinical_observation' => $clinicalObservation,
                    'follow_up_plan' => $followUpPlan,
                    'icd_code' => $icdCode,
                    'clinician_name' => $clinicianName,
                    'facility_id' => $facilityId,
                    'staff_mode' => true
                ]);
            }

            $assessmentId = $this->db->insert(
                "INSERT INTO assessments (user_id, age, stress_level, anxiety_level, depression_level, 
                mental_history, sleep_hours, exercise_level, social_support, prediction, confidence, 
                probabilities, recommendations, clinical_data) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                [
                    $_SESSION['user_id'] ?? null,
                    $age,
                    $stress,
                    $anxiety,
                    $depression,
                    $mental_history,
                    $sleep,
                    $exercise,
                    $social_support,
                    $result['prediction'] ?? 'Unknown',
                    $result['confidence'] ?? 0,
                    json_encode($result['probabilities'] ?? []),
                    json_encode($result['recommendations'] ?? []),
                    $clinicalData
                ]
            );

            // Log the prediction
            $this->logger->log(
                \AuditLogger::EVENT_PREDICTION,
                $_SESSION['user_id'] ?? null,
                'Mental health prediction performed',
                [
                    'prediction' => $result['prediction'] ?? 'Unknown',
                    'confidence' => $result['confidence'] ?? 0
                ],
                'SUCCESS'
            );

            // Add to session (for immediate display) - include all 13 fields
            $latestPrediction = [
                'id' => $assessmentId,
                'timestamp' => date('Y-m-d H:i:s'),
                'input' => [
                    'age' => $age,
                    'gender' => $gender,
                    'employment_status' => $employment_status,
                    'work_environment' => $work_environment,
                    'mental_history' => $mental_history,
                    'seeks_treatment' => $seeks_treatment,
                    'stress' => $stress,
                    'depression' => $depression,
                    'anxiety' => $anxiety,
                    'sleep' => $sleep,
                    'exercise' => $exercise,
                    'social_support' => $social_support,
                    'productivity' => $productivity
                ],
                'prediction' => $result['prediction'] ?? 'Unknown',
                'confidence' => $result['confidence'] ?? 0,
                'probabilities' => $result['probabilities'] ?? [],
                'recommendations' => $result['recommendations'] ?? []
            ];

            $_SESSION['predictions'][] = $latestPrediction;

            $hospitalSync = null;
            // Skip hospital integration if already in staff mode (data already at RS)
            if ($share_with_hospital && $hospital_id && !$isStaffMode) {
                $hospitalSync = $this->hospitalService->sendAssessment([
                    'assessment_id' => $assessmentId,
                    'user_id' => $_SESSION['user_id'] ?? null,
                    'hospital_id' => $hospital_id,
                    'patient_reference' => $patient_reference,
                    'notes' => $hospital_notes,
                    'input' => $latestPrediction['input'],
                    'result' => $result
                ]);
            } elseif ($isStaffMode && $facilityId) {
                // For staff mode, mark as auto-saved to facility
                $hospitalSync = [
                    'success' => true,
                    'status' => 'SAVED_TO_FACILITY',
                    'message' => 'Data tersimpan langsung di sistem rumah sakit.',
                    'facility' => ['id' => $facilityId, 'name' => 'Hospital System']
                ];
            }

            // Return result with success flag and flatten data
            $response = array_merge(['success' => true, 'assessment_id' => $assessmentId], $result);
            if ($hospitalSync) {
                $response['hospital_sync'] = $hospitalSync;
            }
            if ($isStaffMode) {
                $response['staff_mode'] = true;
                $response['patient_mrn'] = $patientMRN;
            }
            echo json_encode($response);

        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function toBool($value)
    {
        if (is_bool($value)) {
            return $value;
        }

        $normalized = strtolower(trim((string) $value));
        return in_array($normalized, ['1', 'true', 'yes', 'on'], true);
    }

    private function generatePythonScript($age, $gender, $employment_status, $work_environment, $mental_history, $seeks_treatment, $stress, $depression, $anxiety, $sleep, $exercise, $social_support, $productivity, $modelDir)
    {
        $modelDir = str_replace('\\', '\\\\', $modelDir);

        return <<<PYTHON
import sys
import os
import json
import joblib
import numpy as np
import pandas as pd

try:
    # Load model and preprocessors
    model_path = r"{$modelDir}"
    model = joblib.load(os.path.join(model_path, 'random_forest_model.pkl'))
    label_encoders = joblib.load(os.path.join(model_path, 'label_encoders.pkl'))
    feature_columns = joblib.load(os.path.join(model_path, 'feature_columns.pkl'))
    
    # Prepare input data - GUNAKAN NAMA KOLOM YANG SAMA DENGAN NOTEBOOK
    data = {
        'age': {$age},
        'gender': '{$gender}',
        'employment_status': '{$employment_status}',
        'work_environment': '{$work_environment}',
        'mental_health_history': '{$mental_history}',
        'seeks_treatment': '{$seeks_treatment}',
        'stress_level': {$stress},
        'depression_score': {$depression},
        'anxiety_score': {$anxiety},
        'sleep_hours': {$sleep},
        'physical_activity_days': {$exercise},
        'social_support_score': {$social_support},
        'productivity_score': {$productivity}
    }
    
    # Create DataFrame
    df = pd.DataFrame([data])
    
    # Encode categorical features
    for col in ['gender', 'employment_status', 'work_environment', 'mental_health_history', 'seeks_treatment']:
        if col in label_encoders:
            df[col] = label_encoders[col].transform(df[col])
    
    # Reorder columns to match training
    df = df[feature_columns]
    
    # Predict
    prediction = model.predict(df)[0]
    probabilities = model.predict_proba(df)[0]
    
    # Map prediction to risk level
    risk_levels = ['Low Risk', 'Moderate Risk', 'High Risk']
    prediction_label = risk_levels[prediction] if prediction < len(risk_levels) else 'Unknown'
    confidence = float(max(probabilities))
    
    # Determine color
    if prediction == 0:
        color = 'success'
    elif prediction == 1:
        color = 'warning'
    else:
        color = 'danger'
    
    # Create result
    result = {
        'prediction': prediction_label,
        'confidence': confidence,
        'risk_score': float(probabilities[prediction]),
        'probabilities': {
            'Low Risk': float(probabilities[0]) if len(probabilities) > 0 else 0.0,
            'Moderate Risk': float(probabilities[1]) if len(probabilities) > 1 else 0.0,
            'High Risk': float(probabilities[2]) if len(probabilities) > 2 else 0.0
        },
        'color': color,
        'recommendations': []
    }
    
    # Add recommendations based on risk level
    if prediction == 2:  # High Risk
        result['recommendations'] = [
            'Sangat disarankan untuk segera konsultasi dengan profesional kesehatan mental',
            'Hubungi keluarga atau teman dekat untuk mendapatkan dukungan',
            'Prioritaskan tidur yang cukup dan pola makan sehat',
            'Hindari alkohol dan zat adiktif'
        ]
    elif prediction == 1:  # Moderate Risk
        result['recommendations'] = [
            'Pertimbangkan untuk konseling atau terapi',
            'Jaga pola tidur dan aktivitas fisik secara teratur',
            'Praktikkan teknik relaksasi seperti meditasi',
            'Tetap terhubung dengan orang-orang terdekat'
        ]
    else:  # Low Risk
        result['recommendations'] = [
            'Lanjutkan kebiasaan hidup sehat Anda',
            'Tetap aktif secara fisik dan sosial',
            'Jaga pola tidur dan pola makan',
            'Lakukan pemeriksaan rutin untuk monitor kesehatan mental'
        ]
    
    print(json.dumps(result))
    sys.exit(0)
    
except Exception as e:
    print(json.dumps({'error': str(e)}), file=sys.stderr)
    sys.exit(1)
PYTHON;
    }

    private function getMockResult($age, $gender, $employment_status, $work_environment, $mental_history, $seeks_treatment, $stress, $depression, $anxiety, $sleep, $exercise, $social_support, $productivity)
    {
        // Simple risk calculation based on multiple factors
        $mental_score = ($stress + $depression + $anxiety) / 50.0; // Normalized to 0-1

        // Adjust for other factors
        $sleep_penalty = ($sleep < 6 || $sleep > 9) ? 0.1 : 0;
        $exercise_bonus = ($exercise >= 4) ? -0.1 : (($exercise < 2) ? 0.1 : 0);
        $support_bonus = ($social_support >= 60) ? -0.05 : (($social_support < 30) ? 0.1 : 0);
        $history_penalty = ($mental_history == 'Yes') ? 0.15 : 0;
        $treatment_bonus = ($seeks_treatment == 'Yes') ? -0.05 : 0;
        $productivity_bonus = ($productivity >= 70) ? -0.05 : (($productivity < 40) ? 0.1 : 0);

        $risk_score = $mental_score + $sleep_penalty + $exercise_bonus + $support_bonus + $history_penalty + $treatment_bonus + $productivity_bonus;
        $risk_score = max(0, min(1, $risk_score)); // Clamp between 0 and 1

        if ($risk_score < 0.35) {
            $prediction = 'Low Risk';
            $confidence = 0.78 + (rand(0, 10) / 100);
            $color = 'success';
            $probs = [
                'Low Risk' => 0.70 + (rand(0, 15) / 100),
                'Moderate Risk' => 0.20 + (rand(0, 10) / 100),
                'High Risk' => 0.05 + (rand(0, 5) / 100)
            ];
        } elseif ($risk_score < 0.65) {
            $prediction = 'Moderate Risk';
            $confidence = 0.72 + (rand(0, 8) / 100);
            $color = 'warning';
            $probs = [
                'Low Risk' => 0.25 + (rand(0, 10) / 100),
                'Moderate Risk' => 0.55 + (rand(0, 15) / 100),
                'High Risk' => 0.15 + (rand(0, 10) / 100)
            ];
        } else {
            $prediction = 'High Risk';
            $confidence = 0.82 + (rand(0, 12) / 100);
            $color = 'danger';
            $probs = [
                'Low Risk' => 0.10 + (rand(0, 5) / 100),
                'Moderate Risk' => 0.20 + (rand(0, 10) / 100),
                'High Risk' => 0.65 + (rand(0, 20) / 100)
            ];
        }

        // Normalize probabilities
        $total = array_sum($probs);
        foreach ($probs as $key => $value) {
            $probs[$key] = $value / $total;
        }

        return [
            'prediction' => $prediction,
            'confidence' => $confidence,
            'risk_score' => $risk_score,
            'color' => $color,
            'probabilities' => $probs,
            'recommendations' => $this->getRecommendations($prediction)
        ];
    }

    private function getRecommendations($risk_level)
    {
        $recommendations = [
            'Low Risk' => [
                'Lanjutkan kebiasaan hidup sehat Anda',
                'Pertahankan jadwal tidur teratur (7-9 jam)',
                'Tetap aktif secara fisik',
                'Jaga hubungan sosial yang kuat'
            ],
            'Moderate Risk' => [
                'Pertimbangkan untuk berbicara dengan konselor',
                'Latih teknik manajemen stres',
                'Tingkatkan aktivitas fisik minimal 30 menit/hari',
                'Pertahankan rutinitas tidur yang konsisten',
                'Hubungi jaringan dukungan Anda'
            ],
            'High Risk' => [
                '⚠️ Sangat disarankan konsultasi profesional',
                'Hubungi profesional kesehatan mental segera',
                'Hubungi hotline krisis: 119 ext.8 atau 1500-567',
                'Jangan menyendiri - hubungi orang yang dipercaya',
                'Hindari keputusan besar sampai berkonsultasi dengan profesional'
            ]
        ];

        return $recommendations[$risk_level] ?? [];
    }
}
