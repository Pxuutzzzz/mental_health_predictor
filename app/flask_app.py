"""
Flask web application for Mental Health Predictor
Modern REST API with Bootstrap 5 frontend
"""

from flask import Flask, render_template, request, jsonify
import sys
import os
from pathlib import Path
import json
import logging

# Add parent directory to path
sys.path.append(str(Path(__file__).parent.parent))

from src.utils import load_config, setup_logging
from src.predictor import MentalHealthPredictor

# Initialize Flask app
app = Flask(__name__, template_folder='../templates')
app.config['SECRET_KEY'] = 'your-secret-key-change-this-in-production'

# Global predictor instance
predictor = None
config = None
model_loaded = False

logger = logging.getLogger(__name__)


def initialize_app():
    """Initialize the Flask application"""
    global predictor, config, model_loaded
    
    try:
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
        model_loaded = True
        logger.info("Flask app initialized with trained model")
        
    except Exception as e:
        logger.error(f"Failed to initialize predictor: {str(e)}")
        logger.warning("Running in demo mode without trained model")
        model_loaded = False


@app.route('/')
def index():
    """Home page"""
    return render_template('index.html', model_loaded=model_loaded)


@app.route('/api/predict', methods=['POST'])
def predict():
    """
    Prediction API endpoint
    
    Expected JSON payload:
    {
        "age": 25,
        "stress_level": 5,
        "anxiety_level": 5,
        "depression_score": 5,
        "mental_history": 0,
        "sleep_hours": 7,
        "exercise_frequency": 1,
        "social_support": 1
    }
    """
    try:
        data = request.get_json()
        
        if not data:
            return jsonify({
                'success': False,
                'error': 'No data provided'
            }), 400
        
        # Validate required fields
        required_fields = [
            'age', 'stress_level', 'anxiety_level', 'depression_score',
            'mental_history', 'sleep_hours', 'exercise_frequency', 'social_support'
        ]
        
        for field in required_fields:
            if field not in data:
                return jsonify({
                    'success': False,
                    'error': f'Missing required field: {field}'
                }), 400
        
        # Make prediction
        if model_loaded and predictor:
            prediction, confidence, probabilities = predictor.predict_with_confidence(data)
        else:
            # Demo prediction
            prediction, confidence, probabilities = demo_prediction(data)
        
        # Get recommendations
        recommendations = get_recommendations(prediction, data)
        
        # Format response
        response = {
            'success': True,
            'prediction': prediction,
            'confidence': float(confidence) if confidence else 0.0,
            'probabilities': probabilities,
            'recommendations': recommendations,
            'risk_level': get_risk_level(prediction),
            'model_loaded': model_loaded
        }
        
        logger.info(f"Prediction made: {prediction} with confidence {confidence}")
        
        return jsonify(response), 200
        
    except Exception as e:
        logger.error(f"Prediction error: {str(e)}")
        return jsonify({
            'success': False,
            'error': str(e)
        }), 500


@app.route('/api/health', methods=['GET'])
def health_check():
    """Health check endpoint"""
    return jsonify({
        'status': 'healthy',
        'model_loaded': model_loaded,
        'version': '1.0.0'
    }), 200


def demo_prediction(data):
    """Demo prediction when model is not available"""
    stress = data.get('stress_level', 5)
    anxiety = data.get('anxiety_level', 5)
    depression = data.get('depression_score', 5)
    
    risk_score = (stress + anxiety + depression) / 30
    
    if risk_score > 0.7:
        prediction = "High Risk"
        confidence = 0.85
    elif risk_score > 0.4:
        prediction = "Moderate Risk"
        confidence = 0.78
    else:
        prediction = "Low Risk"
        confidence = 0.82
    
    probabilities = {
        "Low Risk": max(0, 1 - risk_score),
        "Moderate Risk": 0.3 if risk_score > 0.4 else 0.2,
        "High Risk": max(0, risk_score - 0.3)
    }
    
    return prediction, confidence, probabilities


def get_risk_level(prediction):
    """Get risk level indicator"""
    if "High" in prediction:
        return {
            'level': 'high',
            'color': 'danger',
            'icon': '🔴',
            'text': 'High Risk - Seek Professional Help'
        }
    elif "Moderate" in prediction:
        return {
            'level': 'moderate',
            'color': 'warning',
            'icon': '🟡',
            'text': 'Moderate Risk - Monitor Closely'
        }
    else:
        return {
            'level': 'low',
            'color': 'success',
            'icon': '🟢',
            'text': 'Low Risk - Maintain Healthy Habits'
        }


def get_recommendations(prediction, data):
    """Get personalized recommendations based on prediction"""
    recommendations = []
    
    if "High" in prediction:
        recommendations = [
            "Consider consulting a mental health professional immediately",
            "Reach out to trusted friends or family members",
            "Practice stress-reduction techniques (meditation, deep breathing)",
            "Ensure adequate sleep (7-9 hours per night)",
            "Engage in regular physical activity",
            "Avoid alcohol and substance use",
            "Contact crisis helpline if feeling overwhelmed"
        ]
    elif "Moderate" in prediction:
        recommendations = [
            "Monitor your mental health regularly",
            "Maintain healthy sleep patterns",
            "Practice mindfulness or meditation daily",
            "Stay connected with supportive people",
            "Consider speaking with a counselor or therapist",
            "Exercise at least 30 minutes daily",
            "Limit caffeine and maintain balanced diet"
        ]
    else:
        recommendations = [
            "Continue maintaining healthy lifestyle habits",
            "Stay socially connected with friends and family",
            "Practice regular self-care activities",
            "Monitor for any changes in mood or behavior",
            "Help others who may be struggling",
            "Keep a consistent sleep schedule",
            "Engage in hobbies and activities you enjoy"
        ]
    
    # Add specific recommendations based on input
    if data.get('sleep_hours', 7) < 6:
        recommendations.insert(1, "Prioritize getting more sleep (aim for 7-9 hours)")
    
    if data.get('exercise_frequency', 1) == 0:  # Low exercise
        recommendations.insert(2, "Increase physical activity gradually")
    
    if data.get('social_support', 1) == 0:  # No social support
        recommendations.insert(1, "Build a support network through groups or activities")
    
    return recommendations


def main():
    """Main function to run Flask app"""
    print("=" * 60)
    print("🧠 MENTAL HEALTH PREDICTOR - FLASK WEB APP")
    print("=" * 60)
    print("\n🚀 Starting application...\n")
    
    # Initialize app
    initialize_app()
    
    print("✅ Application initialized successfully!")
    print("\n📱 Access the web interface at:")
    print("   Local:   http://localhost:5000")
    print("   Network: http://0.0.0.0:5000")
    print("\n💡 API Endpoints:")
    print("   POST /api/predict - Make predictions")
    print("   GET  /api/health  - Health check")
    print("\n💡 Press Ctrl+C to stop the server\n")
    print("=" * 60)
    
    # Run Flask app
    app.run(
        host='0.0.0.0',
        port=5000,
        debug=True
    )


if __name__ == '__main__':
    main()
