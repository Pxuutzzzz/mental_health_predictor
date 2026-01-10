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
            'age', 'gender', 'employment_status', 'work_environment',
            'mental_health_history', 'seeks_treatment', 'stress_level',
            'sleep_hours', 'physical_activity_days', 'depression_score',
            'anxiety_score', 'social_support_score', 'productivity_score'
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
    depression = data.get('depression_score', 15)
    anxiety = data.get('anxiety_score', 10)
    
    # Calculate risk score based on mental health indicators
    risk_score = ((stress / 10) * 0.3 + (depression / 50) * 0.4 + (anxiety / 30) * 0.3)
    
    if risk_score > 0.6:
        prediction = "High"
        confidence = 0.85
    elif risk_score > 0.35:
        prediction = "Medium"
        confidence = 0.78
    else:
        prediction = "Low"
        confidence = 0.82
    
    probabilities = {
        "Low": max(0, 1 - risk_score - 0.2),
        "Medium": 0.4 if risk_score > 0.3 else 0.3,
        "High": max(0, risk_score - 0.2)
    }
    
    # Normalize probabilities
    total = sum(probabilities.values())
    probabilities = {k: v/total for k, v in probabilities.items()}
    
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
            "🚨 Consider consulting a mental health professional immediately",
            "👥 Reach out to trusted friends or family members",
            "🧘 Practice stress-reduction techniques (meditation, deep breathing)",
            "😴 Ensure adequate sleep (7-9 hours per night)",
            "🏃 Engage in regular physical activity",
            "🚫 Avoid alcohol and substance use",
            "📞 Contact crisis helpline if feeling overwhelmed (988 Suicide & Crisis Lifeline)"
        ]
    elif "Medium" in prediction:
        recommendations = [
            "📊 Monitor your mental health regularly",
            "😴 Maintain healthy sleep patterns (7-9 hours)",
            "🧘 Practice mindfulness or meditation daily",
            "👫 Stay connected with supportive people",
            "💬 Consider speaking with a counselor or therapist",
            "🏃 Exercise at least 30 minutes daily",
            "🥗 Limit caffeine and maintain balanced diet"
        ]
    else:
        recommendations = [
            "✅ Continue maintaining healthy lifestyle habits",
            "👨‍👩‍👧 Stay socially connected with friends and family",
            "💆 Practice regular self-care activities",
            "📝 Monitor for any changes in mood or behavior",
            "🤝 Help others who may be struggling",
            "😴 Keep a consistent sleep schedule",
            "🎨 Engage in hobbies and activities you enjoy"
        ]
    
    # Add specific recommendations based on input
    if data.get('sleep_hours', 7) < 6:
        recommendations.insert(1, "⚠️ Prioritize getting more sleep (aim for 7-9 hours)")
    
    if data.get('physical_activity_days', 3) < 2:
        recommendations.insert(2, "🏃 Increase physical activity gradually (aim for 3+ days/week)")
    
    if data.get('social_support_score', 50) < 30:
        recommendations.insert(1, "👥 Build a support network through groups or activities")
    
    if data.get('stress_level', 5) >= 8:
        recommendations.insert(1, "😌 Practice stress management techniques immediately")
    
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
