"""
Utility functions for the Mental Health Predictor project
"""

import os
import yaml
import json
import logging
from datetime import datetime
from pathlib import Path


def load_config(config_path="config.yaml"):
    """
    Load configuration from YAML file
    
    Args:
        config_path (str): Path to configuration file
        
    Returns:
        dict: Configuration dictionary
    """
    with open(config_path, 'r') as file:
        config = yaml.safe_load(file)
    return config


def setup_logging(log_dir="logs", log_file=None, level="INFO"):
    """
    Setup logging configuration
    
    Args:
        log_dir (str): Directory for log files
        log_file (str): Log file name
        level (str): Logging level
    """
    # Create log directory if it doesn't exist
    os.makedirs(log_dir, exist_ok=True)
    
    # Generate log file name if not provided
    if log_file is None:
        timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
        log_file = os.path.join(log_dir, f"log_{timestamp}.log")
    
    # Configure logging
    logging.basicConfig(
        level=getattr(logging, level.upper()),
        format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
        handlers=[
            logging.FileHandler(log_file),
            logging.StreamHandler()
        ]
    )
    
    return logging.getLogger(__name__)


def create_directories(config):
    """
    Create necessary directories for the project
    
    Args:
        config (dict): Configuration dictionary
    """
    directories = [
        config['logging']['log_dir'],
        config['results']['results_dir'],
        os.path.dirname(config['model']['model_path']),
        os.path.dirname(config['data']['processed_data_path'])
    ]
    
    for directory in directories:
        os.makedirs(directory, exist_ok=True)


def save_json(data, filepath):
    """
    Save data to JSON file
    
    Args:
        data: Data to save
        filepath (str): Path to save file
    """
    os.makedirs(os.path.dirname(filepath), exist_ok=True)
    with open(filepath, 'w') as f:
        json.dump(data, f, indent=4)


def load_json(filepath):
    """
    Load data from JSON file
    
    Args:
        filepath (str): Path to JSON file
        
    Returns:
        Data from JSON file
    """
    with open(filepath, 'r') as f:
        return json.load(f)


def get_project_root():
    """
    Get the project root directory
    
    Returns:
        Path: Project root path
    """
    return Path(__file__).parent.parent
