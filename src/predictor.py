import logging
import joblib
import pandas as pd
import numpy as np
import os

logger = logging.getLogger(__name__)

class MentalHealthPredictor:
    def __init__(self, config):
        self.config = config
        self.model = None
        self.label_encoders = None
        self.feature_columns = None
        
    def initialize(self):
        """Load trained models and artifacts"""
        try:
            model_path = self.config.get('model', {}).get('model_path', 'models/random_forest_model.pkl')
            le_path = self.config.get('model', {}).get('label_encoders_path', 'models/label_encoders.pkl')
            fc_path = self.config.get('model', {}).get('feature_columns_path', 'models/feature_columns.pkl')
            
            if os.path.exists(model_path):
                self.model = joblib.load(model_path)
                logger.info(f"Loaded model from {model_path}")
            
            if os.path.exists(le_path):
                self.label_encoders = joblib.load(le_path)
                logger.info(f"Loaded label encoders from {le_path}")
                
            if os.path.exists(fc_path):
                self.feature_columns = joblib.load(fc_path)
                logger.info(f"Loaded feature columns from {fc_path}")
                
        except Exception as e:
            logger.error(f"Error checking initializing model: {e}")
            # Do not raise, allow fallback to demo mode
            
    def predict_with_confidence(self, data):
        """
        Make prediction with confidence score and probabilities
        Returns: (prediction_label, confidence_score, probability_dict)
        """
        try:
            if self.model and self.feature_columns:
                # 1. Prepare input dataframe
                df = pd.DataFrame([data])
                
                # 2. Encode categorical variables if encoders exist
                if self.label_encoders:
                    for col, le in self.label_encoders.items():
                        if col in df.columns:
                            # Handle unknown labels safely
                            try:
                                df[col] = le.transform(df[col])
                            except:
                                # Fallback for unknown categories (e.g., use most frequent or 0)
                                df[col] = 0
                                
                # 3. Ensure all columns exist and are in correct order
                for col in self.feature_columns:
                    if col not in df.columns:
                        df[col] = 0 # Default value
                
                df = df[self.feature_columns]
                
                # 4. Predict
                probs = self.model.predict_proba(df)[0]
                pred_idx = np.argmax(probs)
                confidence = probs[pred_idx]
                
                # Map prediction to label (assuming classes are sorted/standard)
                classes = self.model.classes_
                prediction = classes[pred_idx]
                
                prob_dict = {str(k): float(v) for k, v in zip(classes, probs)}
                
                return prediction, confidence, prob_dict
                
        except Exception as e:
            logger.error(f"Prediction failed, falling back to rule-based: {e}")
            
        # Fallback Logic (Rule-based)
        return self._rule_based_prediction(data)
        
    def _rule_based_prediction(self, data):
        """Fallback prediction logic based on simple rules"""
        stress = float(data.get('stress_level', 5))
        depression = float(data.get('depression_score', 5))
        anxiety = float(data.get('anxiety_score', 5))
        
        # Calculate a simple risk score
        risk_score = ((stress / 10) * 0.3 + (depression / 10) * 0.4 + (anxiety / 10) * 0.3)
        
        if risk_score > 0.7:
            prediction = "High"
            confidence = 0.85
        elif risk_score > 0.4:
            prediction = "Medium"
            confidence = 0.75
        else:
            prediction = "Low"
            confidence = 0.80
            
        probabilities = {
            "Low": max(0.0, 1 - risk_score),
            "Medium": 0.5 - abs(0.5 - risk_score),
            "High": max(0.0, risk_score)
        }
        
        # Normalize
        total = sum(probabilities.values())
        if total > 0:
            probabilities = {k: v/total for k, v in probabilities.items()}
            
        return prediction, confidence, probabilities
