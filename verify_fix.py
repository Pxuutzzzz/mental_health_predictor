import sys
import os
from pathlib import Path

# Add current directory to path
sys.path.append(os.getcwd())

try:
    from src.utils import load_config, setup_logging
    from src.predictor import MentalHealthPredictor
    print("SUCCESS: Modules imported successfully")
except Exception as e:
    print(f"FAILURE: {e}")
