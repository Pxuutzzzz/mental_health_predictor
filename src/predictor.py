"""
Predictor module for Mental Health Predictor
"""

import pickle
import numpy as np
import pandas as pd
import logging
from pathlib import Path

logger = logging.getLogger(__name__)


class MentalHealthPredictor:
    """
    Class for making mental health predictions
    """
    
    def __init__(self, config):
        """
        Initialize MentalHealthPredictor
        
        Args:
            config (dict): Configuration dictionary
        """
        self.config = config
        self.model = None
        self.scaler = None
        self.label_encoder = None
        self.feature_columns = None
        
    def load_model(self):
        """
        Load the trained model
        """
        model_path = self.config['model']['model_path']
        logger.info(f"Loading model from {model_path}")
        
        try:
            with open(model_path, 'rb') as f:
                self.model = pickle.load(f)
            logger.info("Model loaded successfully")
        except FileNotFoundError:
            logger.error(f"Model file not found at {model_path}")
            raise
        except Exception as e:
            logger.error(f"Error loading model: {str(e)}")
            raise
    
    def load_preprocessors(self):
        """
        Load scaler and label encoder
        """
        scaler_path = self.config['model']['scaler_path']
        label_encoder_path = self.config['model']['label_encoder_path']
        
        logger.info(f"Loading preprocessors")
        
        try:
            with open(scaler_path, 'rb') as f:
                self.scaler = pickle.load(f)
            
            with open(label_encoder_path, 'rb') as f:
                self.label_encoder = pickle.load(f)
            
            logger.info("Preprocessors loaded successfully")
        except FileNotFoundError as e:
            logger.error(f"Preprocessor file not found: {str(e)}")
            raise
        except Exception as e:
            logger.error(f"Error loading preprocessors: {str(e)}")
            raise
    
    def initialize(self):
        """
        Initialize predictor by loading model and preprocessors
        """
        logger.info("Initializing predictor")
        self.load_model()
        self.load_preprocessors()
        logger.info("Predictor initialized successfully")
    
    def preprocess_input(self, input_data):
        """
        Preprocess input data for prediction
        
        Args:
            input_data (dict or pd.DataFrame): Input data
            
        Returns:
            np.array: Preprocessed data
        """
        logger.info("Preprocessing input data")
        
        # Convert to DataFrame if dict
        if isinstance(input_data, dict):
            df = pd.DataFrame([input_data])
        else:
            df = input_data.copy()
        
        # Handle categorical features (if any)
        # Note: You may need to adjust this based on your specific data
        categorical_cols = df.select_dtypes(include=['object']).columns
        for col in categorical_cols:
            df[col] = df[col].astype(str)
        
        # Scale features
        X = self.scaler.transform(df)
        
        logger.info("Input data preprocessed")
        return X
    
    def predict(self, input_data):
        """
        Make prediction on input data
        
        Args:
            input_data (dict or pd.DataFrame): Input data
            
        Returns:
            str: Predicted class label
        """
        logger.info("Making prediction")
        
        if self.model is None:
            logger.error("Model not loaded. Call initialize() first.")
            raise RuntimeError("Model not loaded. Call initialize() first.")
        
        # Preprocess input
        X = self.preprocess_input(input_data)
        
        # Make prediction
        prediction = self.model.predict(X)
        
        # Decode prediction if label encoder exists
        if self.label_encoder is not None:
            try:
                prediction_label = self.label_encoder.inverse_transform(prediction)[0]
            except:
                prediction_label = str(prediction[0])
        else:
            prediction_label = str(prediction[0])
        
        logger.info(f"Prediction: {prediction_label}")
        return prediction_label
    
    def predict_proba(self, input_data):
        """
        Get prediction probabilities
        
        Args:
            input_data (dict or pd.DataFrame): Input data
            
        Returns:
            dict: Dictionary of class probabilities
        """
        logger.info("Getting prediction probabilities")
        
        if self.model is None:
            logger.error("Model not loaded. Call initialize() first.")
            raise RuntimeError("Model not loaded. Call initialize() first.")
        
        # Check if model supports predict_proba
        if not hasattr(self.model, 'predict_proba'):
            logger.warning("Model does not support probability predictions")
            return {}
        
        # Preprocess input
        X = self.preprocess_input(input_data)
        
        # Get probabilities
        probabilities = self.model.predict_proba(X)[0]
        
        # Create dictionary with class labels
        if self.label_encoder is not None:
            classes = self.label_encoder.classes_
        else:
            classes = self.model.classes_
        
        proba_dict = {
            str(class_label): float(prob) 
            for class_label, prob in zip(classes, probabilities)
        }
        
        logger.info(f"Probabilities: {proba_dict}")
        return proba_dict
    
    def predict_with_confidence(self, input_data):
        """
        Make prediction with confidence score
        
        Args:
            input_data (dict or pd.DataFrame): Input data
            
        Returns:
            tuple: (prediction, confidence, probabilities)
        """
        prediction = self.predict(input_data)
        probabilities = self.predict_proba(input_data)
        
        # Get confidence (max probability)
        if probabilities:
            confidence = max(probabilities.values())
        else:
            confidence = None
        
        return prediction, confidence, probabilities


def main():
    """
    Main function for testing the predictor
    """
    import sys
    from pathlib import Path
    sys.path.insert(0, str(Path(__file__).parent.parent))
    
    from src.utils import load_config, setup_logging
    
    # Load configuration
    config = load_config()
    
    # Setup logging
    setup_logging(
        log_dir=config['logging']['log_dir'],
        level=config['logging']['level']
    )
    
    # Initialize predictor
    predictor = MentalHealthPredictor(config)
    predictor.initialize()
    
    # Example prediction (adjust based on your features)
    example_input = {
        'feature1': 25,
        'feature2': 1,
        'feature3': 0,
        # Add more features as needed
    }
    
    prediction, confidence, probabilities = predictor.predict_with_confidence(example_input)
    
    print(f"\nPrediction: {prediction}")
    print(f"Confidence: {confidence:.2%}")
    print(f"Probabilities: {probabilities}")


if __name__ == "__main__":
    main()
