import yaml
import logging
import os
from pathlib import Path

def load_config(config_path='config.yaml'):
    """Load configuration from YAML file"""
    try:
        # Try to find config file in current directory or parent directory
        if os.path.exists(config_path):
            with open(config_path, 'r') as f:
                return yaml.safe_load(f)
        
        parent_config = os.path.join('..', config_path)
        if os.path.exists(parent_config):
            with open(parent_config, 'r') as f:
                return yaml.safe_load(f)
                
        # Default config if not found
        return {
            'logging': {'log_dir': 'logs', 'level': 'INFO'},
            'model': {'model_path': 'models/random_forest_model.pkl'}
        }
    except Exception as e:
        print(f"Error loading config: {e}")
        return {}

def setup_logging(log_dir='logs', level='INFO'):
    """Setup logging configuration"""
    if not os.path.exists(log_dir):
        os.makedirs(log_dir, exist_ok=True)
        
    logging.basicConfig(
        level=getattr(logging, level.upper(), logging.INFO),
        format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
        handlers=[
            logging.StreamHandler(),
            logging.FileHandler(os.path.join(log_dir, 'app.log'))
        ]
    )
