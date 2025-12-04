"""
Model training module for Mental Health Predictor
"""

import numpy as np
import pandas as pd
import pickle
import logging
import json
import matplotlib
matplotlib.use('Agg')  # Use non-interactive backend
import matplotlib.pyplot as plt
import seaborn as sns
from sklearn.ensemble import RandomForestClassifier
from sklearn.linear_model import LogisticRegression
from sklearn.metrics import (
    accuracy_score, precision_score, recall_score, 
    f1_score, confusion_matrix, classification_report
)

logger = logging.getLogger(__name__)


class ModelTrainer:
    """
    Class for training mental health prediction models
    """
    
    def __init__(self, config):
        """
        Initialize ModelTrainer
        
        Args:
            config (dict): Configuration dictionary
        """
        self.config = config
        self.model = None
        self.metrics = {}
        
    def create_model(self):
        """
        Create model based on configuration
        
        Returns:
            Model object
        """
        algorithm = self.config['model']['algorithm']
        logger.info(f"Creating {algorithm} model")
        
        if algorithm == 'random_forest':
            self.model = RandomForestClassifier(
                n_estimators=self.config['random_forest']['n_estimators'],
                max_depth=self.config['random_forest']['max_depth'],
                min_samples_split=self.config['random_forest']['min_samples_split'],
                min_samples_leaf=self.config['random_forest']['min_samples_leaf'],
                random_state=self.config['random_forest']['random_state']
            )
        elif algorithm == 'logistic_regression':
            self.model = LogisticRegression(
                max_iter=self.config['logistic_regression']['max_iter'],
                random_state=self.config['logistic_regression']['random_state']
            )
        elif algorithm == 'xgboost':
            try:
                from xgboost import XGBClassifier
                self.model = XGBClassifier(
                    n_estimators=self.config['xgboost']['n_estimators'],
                    max_depth=self.config['xgboost']['max_depth'],
                    learning_rate=self.config['xgboost']['learning_rate'],
                    random_state=self.config['xgboost']['random_state']
                )
            except ImportError:
                logger.error("XGBoost not installed. Please install it using: pip install xgboost")
                raise
        else:
            logger.error(f"Unknown algorithm: {algorithm}")
            raise ValueError(f"Unknown algorithm: {algorithm}")
        
        logger.info(f"{algorithm} model created successfully")
        return self.model
    
    def train(self, X_train, y_train):
        """
        Train the model
        
        Args:
            X_train: Training features
            y_train: Training labels
        """
        logger.info("Starting model training")
        
        if self.model is None:
            self.create_model()
        
        self.model.fit(X_train, y_train)
        logger.info("Model training completed")
    
    def evaluate(self, X_test, y_test):
        """
        Evaluate the model
        
        Args:
            X_test: Test features
            y_test: Test labels
            
        Returns:
            dict: Evaluation metrics
        """
        logger.info("Evaluating model")
        
        y_pred = self.model.predict(X_test)
        
        # Calculate metrics
        self.metrics = {
            'accuracy': float(accuracy_score(y_test, y_pred)),
            'precision': float(precision_score(y_test, y_pred, average='weighted')),
            'recall': float(recall_score(y_test, y_pred, average='weighted')),
            'f1_score': float(f1_score(y_test, y_pred, average='weighted'))
        }
        
        logger.info(f"Model evaluation completed:")
        logger.info(f"  Accuracy: {self.metrics['accuracy']:.4f}")
        logger.info(f"  Precision: {self.metrics['precision']:.4f}")
        logger.info(f"  Recall: {self.metrics['recall']:.4f}")
        logger.info(f"  F1-Score: {self.metrics['f1_score']:.4f}")
        
        # Generate classification report
        report = classification_report(y_test, y_pred)
        logger.info(f"\nClassification Report:\n{report}")
        
        return self.metrics
    
    def plot_confusion_matrix(self, X_test, y_test):
        """
        Plot and save confusion matrix
        
        Args:
            X_test: Test features
            y_test: Test labels
        """
        logger.info("Generating confusion matrix")
        
        y_pred = self.model.predict(X_test)
        cm = confusion_matrix(y_test, y_pred)
        
        plt.figure(figsize=(10, 8))
        sns.heatmap(cm, annot=True, fmt='d', cmap='Blues')
        plt.title('Confusion Matrix')
        plt.ylabel('True Label')
        plt.xlabel('Predicted Label')
        
        output_path = self.config['results']['confusion_matrix_file']
        plt.savefig(output_path, bbox_inches='tight', dpi=300)
        plt.close()
        
        logger.info(f"Confusion matrix saved to {output_path}")
    
    def plot_feature_importance(self, feature_names):
        """
        Plot and save feature importance
        
        Args:
            feature_names (list): List of feature names
        """
        if not hasattr(self.model, 'feature_importances_'):
            logger.warning("Model does not have feature_importances_ attribute")
            return
        
        logger.info("Generating feature importance plot")
        
        importances = self.model.feature_importances_
        indices = np.argsort(importances)[::-1]
        
        # Plot top 20 features
        top_n = min(20, len(feature_names))
        
        plt.figure(figsize=(12, 8))
        plt.title('Feature Importance')
        plt.bar(range(top_n), importances[indices[:top_n]])
        plt.xticks(range(top_n), [feature_names[i] for i in indices[:top_n]], rotation=45, ha='right')
        plt.xlabel('Features')
        plt.ylabel('Importance')
        plt.tight_layout()
        
        output_path = self.config['results']['feature_importance_file']
        plt.savefig(output_path, bbox_inches='tight', dpi=300)
        plt.close()
        
        logger.info(f"Feature importance plot saved to {output_path}")
    
    def save_model(self):
        """
        Save the trained model
        """
        logger.info("Saving model")
        
        model_path = self.config['model']['model_path']
        
        with open(model_path, 'wb') as f:
            pickle.dump(self.model, f)
        
        logger.info(f"Model saved to {model_path}")
    
    def save_metrics(self):
        """
        Save evaluation metrics
        """
        logger.info("Saving metrics")
        
        metrics_path = self.config['results']['metrics_file']
        
        with open(metrics_path, 'w') as f:
            json.dump(self.metrics, f, indent=4)
        
        logger.info(f"Metrics saved to {metrics_path}")
    
    def load_model(self):
        """
        Load a trained model
        """
        logger.info("Loading model")
        
        model_path = self.config['model']['model_path']
        
        with open(model_path, 'rb') as f:
            self.model = pickle.load(f)
        
        logger.info(f"Model loaded from {model_path}")


def main():
    """
    Main function for training the model
    """
    # Import here to avoid circular imports
    import sys
    import os
    from pathlib import Path
    
    # Add parent directory to path
    sys.path.insert(0, str(Path(__file__).parent.parent))
    
    from src.utils import load_config, setup_logging, create_directories
    from src.data_preprocess import DataPreprocessor
    
    # Load configuration
    config = load_config()
    
    # Setup logging
    setup_logging(
        log_dir=config['logging']['log_dir'],
        log_file=config['logging']['log_file'],
        level=config['logging']['level']
    )
    
    logger.info("Starting model training pipeline")
    
    # Create directories
    create_directories(config)
    
    # Preprocess data
    preprocessor = DataPreprocessor(config)
    df = preprocessor.load_data()
    
    # Determine target column (you may need to adjust this based on your data)
    target_column = df.columns[-1]  # Assuming last column is target
    logger.info(f"Using '{target_column}' as target column")
    
    X_train, X_test, y_train, y_test = preprocessor.prepare_data(df, target_column)
    preprocessor.save_preprocessors()
    
    # Train model
    trainer = ModelTrainer(config)
    trainer.train(X_train, y_train)
    
    # Evaluate model
    trainer.evaluate(X_test, y_test)
    
    # Plot results
    trainer.plot_confusion_matrix(X_test, y_test)
    trainer.plot_feature_importance(preprocessor.feature_columns)
    
    # Save model and metrics
    trainer.save_model()
    trainer.save_metrics()
    
    logger.info("Model training pipeline completed successfully")


if __name__ == "__main__":
    main()
