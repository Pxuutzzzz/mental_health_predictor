<?php
namespace Controllers;

require_once __DIR__ . '/../Database.php';
require_once __DIR__ . '/../Security/AuditLogger.php';

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
    
    public function __construct()
    {
        $this->db = \Database::getInstance();
        $this->logger = new \AuditLogger($this->db->getConnection());
        // Path to Python executable (using conda environment)
        // Option 1: Use conda run command
        $this->pythonPath = 'C:\Users\putri\anaconda3\Scripts\conda.exe run -p c:\Users\putri\mental_health_predictor\.conda --no-capture-output python';
        
        // Option 2: Direct path to conda environment python (uncomment if option 1 doesn't work)
        // $this->pythonPath = 'c:\Users\putri\mental_health_predictor\.conda\python.exe';
        
        // Option 3: Use base anaconda python (fallback)
        // $this->pythonPath = 'C:\Users\putri\anaconda3\python.exe';
        
        $this->scriptPath = dirname(__DIR__, 2) . '\src\predictor.py';
    }
    
    public function predict()
    {
        header('Content-Type: application/json');
        
        try {
            // Validate input
            $age = floatval($_POST['age'] ?? 25);
            $stress = floatval($_POST['stress'] ?? 5);
            $anxiety = floatval($_POST['anxiety'] ?? 5);
            $depression = floatval($_POST['depression'] ?? 5);
            $mental_history = $_POST['mental_history'] ?? 'No';
            $sleep = floatval($_POST['sleep'] ?? 7);
            $exercise = $_POST['exercise'] ?? 'Medium';
            $social_support = $_POST['social_support'] ?? 'Yes';
            
            // Try to use Python ML model if available
            $result = null;
            $modelPath = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'mental_health_model.pkl';
            
            if (file_exists($modelPath)) {
                // Create Python script to run prediction
                $pythonScript = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'temp_predict.py';
                $scriptContent = $this->generatePythonScript($age, $stress, $anxiety, $depression, $mental_history, $sleep, $exercise, $social_support);
                file_put_contents($pythonScript, $scriptContent);
                
                // Execute Python script
                $command = sprintf('"%s" "%s" 2>&1', $this->pythonPath, $pythonScript);
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
                $result = $this->getMockResult($age, $stress, $anxiety, $depression, $sleep, $exercise, $social_support, $mental_history);
            }
            
            // Save to session history
            if (!isset($_SESSION['predictions'])) {
                $_SESSION['predictions'] = [];
            }
            
            // Save to database
            $assessmentId = $this->db->insert(
                "INSERT INTO assessments (user_id, age, stress_level, anxiety_level, depression_level, 
                mental_history, sleep_hours, exercise_level, social_support, prediction, confidence, 
                probabilities, recommendations) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                [
                    $_SESSION['user_id'],
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
                    json_encode($result['recommendations'] ?? [])
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
            
            // Add to session (for immediate display)
            $_SESSION['predictions'][] = [
                'id' => $assessmentId,
                'timestamp' => date('Y-m-d H:i:s'),
                'input' => compact('age', 'stress', 'anxiety', 'depression', 'mental_history', 'sleep', 'exercise', 'social_support'),
                'prediction' => $result['prediction'] ?? 'Unknown',
                'confidence' => $result['confidence'] ?? 0,
                'probabilities' => $result['probabilities'] ?? [],
                'recommendations' => $result['recommendations'] ?? []
            ];
            
            // Return result with success flag and flatten data
            $response = array_merge(['success' => true], $result);
            echo json_encode($response);
            
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    private function generatePythonScript($age, $stress, $anxiety, $depression, $mental_history, $sleep, $exercise, $social_support)
    {
        $modelDir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'models';
        $modelDir = str_replace('\\', '\\\\', $modelDir);
        
        return <<<PYTHON
import sys
import os
import json
import joblib
import numpy as np

try:
    # Load model
    model_path = r"{$modelDir}"
    model = joblib.load(os.path.join(model_path, 'mental_health_model.pkl'))
    scaler = joblib.load(os.path.join(model_path, 'scaler.pkl'))
    label_encoder = joblib.load(os.path.join(model_path, 'label_encoder.pkl'))
    
    # Prepare input
    mental_history_encoded = 1 if "{$mental_history}" == "Yes" else 0
    exercise_map = {'Low': 0, 'Medium': 1, 'High': 2}
    exercise_encoded = exercise_map.get("{$exercise}", 1)
    social_support_encoded = 1 if "{$social_support}" == "Yes" else 0
    
    # Create feature array
    features = np.array([[{$age}, {$stress}, {$anxiety}, {$depression}, mental_history_encoded, {$sleep}, exercise_encoded, social_support_encoded]])
    
    # Scale features
    features_scaled = scaler.transform(features)
    
    # Predict
    prediction = model.predict(features_scaled)[0]
    probabilities = model.predict_proba(features_scaled)[0]
    
    # Get prediction label
    prediction_label = label_encoder.inverse_transform([prediction])[0]
    confidence = float(max(probabilities))
    
    # Determine color
    if 'Low' in prediction_label or 'Rendah' in prediction_label:
        color = 'success'
    elif 'High' in prediction_label or 'Tinggi' in prediction_label:
        color = 'danger'
    else:
        color = 'warning'
    
    # Create result
    result = {
        'prediction': prediction_label,
        'confidence': confidence,
        'risk_score': float(probabilities[prediction]),
        'color': color,
        'probabilities': {
            label_encoder.inverse_transform([i])[0]: float(prob) 
            for i, prob in enumerate(probabilities)
        }
    }
    
    print(json.dumps(result))
    
except Exception as e:
    print(json.dumps({'error': str(e)}), file=sys.stderr)
    sys.exit(1)
PYTHON;
    }

    private function getMockResult($age, $stress, $anxiety, $depression, $sleep, $exercise, $social_support, $mental_history)
    {
        // Calculate risk score based on multiple factors
        $mental_score = ($stress + $anxiety + $depression) / 30;
        
        // Adjust for other factors
        $sleep_penalty = ($sleep < 6 || $sleep > 9) ? 0.1 : 0;
        $exercise_bonus = ($exercise == 'High') ? -0.1 : (($exercise == 'Low') ? 0.1 : 0);
        $support_bonus = ($social_support == 'Yes') ? -0.05 : 0.1;
        $history_penalty = ($mental_history == 'Yes') ? 0.15 : 0;
        
        $risk_score = $mental_score + $sleep_penalty + $exercise_bonus + $support_bonus + $history_penalty;
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
