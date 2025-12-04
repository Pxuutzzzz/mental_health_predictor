"""
Data preprocessing module for Mental Health Predictor
"""

import pandas as pd
import numpy as np
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import LabelEncoder, StandardScaler
import logging
import pickle

logger = logging.getLogger(__name__)


class DataPreprocessor:
    """
    Class for preprocessing mental health data
    """
    
    def __init__(self, config):
        """
        Initialize DataPreprocessor
        
        Args:
            config (dict): Configuration dictionary
        """
        self.config = config
        self.label_encoder = LabelEncoder()
        self.scaler = StandardScaler()
        self.feature_columns = None
        self.target_column = None
        
    def load_data(self, filepath=None):
        """
        Load data from CSV file
        
        Args:
            filepath (str): Path to CSV file
            
        Returns:
            pd.DataFrame: Loaded dataframe
        """
        if filepath is None:
            filepath = self.config['data']['raw_data_path']
        
        logger.info(f"Loading data from {filepath}")
        df = pd.read_csv(filepath)
        logger.info(f"Data loaded successfully. Shape: {df.shape}")
        
        return df
    
    def handle_missing_values(self, df):
        """
        Handle missing values in the dataframe
        
        Args:
            df (pd.DataFrame): Input dataframe
            
        Returns:
            pd.DataFrame: Dataframe with handled missing values
        """
        logger.info("Handling missing values")
        
        # Check for missing values
        missing_counts = df.isnull().sum()
        if missing_counts.sum() > 0:
            logger.warning(f"Missing values found:\n{missing_counts[missing_counts > 0]}")
            
            # Fill numeric columns with median
            numeric_cols = df.select_dtypes(include=[np.number]).columns
            for col in numeric_cols:
                if df[col].isnull().sum() > 0:
                    df[col].fillna(df[col].median(), inplace=True)
            
            # Fill categorical columns with mode
            categorical_cols = df.select_dtypes(include=['object']).columns
            for col in categorical_cols:
                if df[col].isnull().sum() > 0:
                    df[col].fillna(df[col].mode()[0], inplace=True)
        
        logger.info("Missing values handled")
        return df
    
    def encode_categorical_features(self, df, fit=True):
        """
        Encode categorical features
        
        Args:
            df (pd.DataFrame): Input dataframe
            fit (bool): Whether to fit the encoder
            
        Returns:
            pd.DataFrame: Dataframe with encoded features
        """
        logger.info("Encoding categorical features")
        
        categorical_cols = df.select_dtypes(include=['object']).columns
        
        for col in categorical_cols:
            if col != self.target_column:
                le = LabelEncoder()
                if fit:
                    df[col] = le.fit_transform(df[col].astype(str))
                else:
                    df[col] = le.transform(df[col].astype(str))
        
        logger.info("Categorical features encoded")
        return df
    
    def scale_features(self, X, fit=True):
        """
        Scale numerical features
        
        Args:
            X (pd.DataFrame or np.array): Features to scale
            fit (bool): Whether to fit the scaler
            
        Returns:
            np.array: Scaled features
        """
        logger.info("Scaling features")
        
        if fit:
            X_scaled = self.scaler.fit_transform(X)
        else:
            X_scaled = self.scaler.transform(X)
        
        logger.info("Features scaled")
        return X_scaled
    
    def prepare_data(self, df, target_column='target'):
        """
        Prepare data for training
        
        Args:
            df (pd.DataFrame): Input dataframe
            target_column (str): Name of target column
            
        Returns:
            tuple: X_train, X_test, y_train, y_test
        """
        logger.info("Preparing data for training")
        
        self.target_column = target_column
        
        # Handle missing values
        df = self.handle_missing_values(df)
        
        # Separate features and target
        if target_column in df.columns:
            X = df.drop(columns=[target_column])
            y = df[target_column]
        else:
            logger.error(f"Target column '{target_column}' not found in dataframe")
            raise ValueError(f"Target column '{target_column}' not found")
        
        # Store feature columns
        self.feature_columns = X.columns.tolist()
        
        # Encode categorical features
        X = self.encode_categorical_features(X, fit=True)
        
        # Encode target if categorical
        if y.dtype == 'object':
            y = self.label_encoder.fit_transform(y)
        
        # Split data
        test_size = self.config['data']['test_size']
        random_state = self.config['data']['random_state']
        
        X_train, X_test, y_train, y_test = train_test_split(
            X, y, test_size=test_size, random_state=random_state, stratify=y
        )
        
        logger.info(f"Train set size: {X_train.shape[0]}, Test set size: {X_test.shape[0]}")
        
        # Scale features
        X_train = self.scale_features(X_train, fit=True)
        X_test = self.scale_features(X_test, fit=False)
        
        logger.info("Data preparation completed")
        
        return X_train, X_test, y_train, y_test
    
    def save_preprocessors(self):
        """
        Save scaler and label encoder
        """
        logger.info("Saving preprocessors")
        
        scaler_path = self.config['model']['scaler_path']
        label_encoder_path = self.config['model']['label_encoder_path']
        
        with open(scaler_path, 'wb') as f:
            pickle.dump(self.scaler, f)
        
        with open(label_encoder_path, 'wb') as f:
            pickle.dump(self.label_encoder, f)
        
        logger.info(f"Preprocessors saved to {scaler_path} and {label_encoder_path}")
    
    def load_preprocessors(self):
        """
        Load scaler and label encoder
        """
        logger.info("Loading preprocessors")
        
        scaler_path = self.config['model']['scaler_path']
        label_encoder_path = self.config['model']['label_encoder_path']
        
        with open(scaler_path, 'rb') as f:
            self.scaler = pickle.load(f)
        
        with open(label_encoder_path, 'rb') as f:
            self.label_encoder = pickle.load(f)
        
        logger.info("Preprocessors loaded successfully")
