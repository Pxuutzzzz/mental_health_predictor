"""
Gradio web application for Mental Health Predictor
Modern UI with custom styling and responsive design
"""

import gradio as gr
import sys
import os
from pathlib import Path
import pandas as pd
import plotly.graph_objects as go
import plotly.express as px

# Add parent directory to path
sys.path.append(str(Path(__file__).parent.parent))

from src.utils import load_config, setup_logging
from src.predictor import MentalHealthPredictor
import logging

logger = logging.getLogger(__name__)

# Custom CSS untuk UI Profesional
CUSTOM_CSS = """
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

:root {
    --primary-color: #3182ce;
    --primary-dark: #2c5282;
    --secondary-color: #4299e1;
    --bg-main: #f5f7fa;
    --bg-white: #ffffff;
    --bg-sidebar: #1a202c;
    --text-primary: #1a202c;
    --text-secondary: #4a5568;
    --text-muted: #718096;
    --border-color: #e2e8f0;
    --border-light: #edf2f7;
    --success: #48bb78;
    --warning: #ed8936;
    --danger: #f56565;
    --info: #4299e1;
}

.gradio-container {
    max-width: 100% !important;
    margin: 0 !important;
    font-family: 'Inter', 'Segoe UI', system-ui, sans-serif !important;
    background: var(--bg-main);
    padding: 0;
    color: var(--text-primary);
}

.main-content-wrapper {
    overflow-y: auto;
    overflow-x: hidden;
}

.tab-content {
    overflow-y: auto;
    padding: 1.5rem;
}

.header-title {
    text-align: left;
    background: var(--bg-white);
    color: var(--text-primary);
    padding: 1.5rem 2rem;
    border-bottom: 1px solid var(--border-color);
    margin: 0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.header-title h1 {
    color: var(--text-primary) !important;
}

.header-title p {
    color: var(--text-muted) !important;
}

.main-card {
    background: var(--bg-white);
    border-radius: 6px;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    margin-bottom: 1rem;
    border: 1px solid var(--border-color);
}

.main-card h2 {
    color: var(--text-primary) !important;
    margin-bottom: 0.5rem;
    font-size: 1.25rem !important;
}

.main-card p {
    color: var(--text-secondary) !important;
    font-size: 0.9rem;
    margin: 0;
}

.compact-section {
    margin-bottom: 1rem;
}

.compact-section h3 {
    font-size: 1rem;
    margin-bottom: 0.5rem;
}

.prediction-box {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    padding: 2.5rem;
    border-radius: 20px;
    color: white;
    font-size: 1.3rem;
    text-align: center;
    box-shadow: 0 15px 40px rgba(245, 87, 108, 0.4);
    animation: pulse 2s infinite;
}

.info-card {
    background: #f8fafc;
    padding: 1rem;
    border-radius: 6px;
    border-left: 3px solid var(--primary-color);
    margin: 0.75rem 0;
    border: 1px solid var(--border-color);
    font-size: 0.9rem;
}

.info-card strong {
    color: var(--text-primary);
}

.info-card p {
    margin: 0.5rem 0;
    line-height: 1.5;
}

.feature-input {
    margin: 1rem 0;
    padding: 10px;
}

.slider-label {
    font-weight: 500;
    color: var(--text-primary);
    font-size: 0.95rem;
}

.result-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-primary);
}

label {
    color: var(--text-primary) !important;
    font-weight: 500 !important;
}

.gr-form label {
    color: var(--text-primary) !important;
}

.gr-input, .gr-box {
    border-color: var(--border-color) !important;
}

.gr-input:focus, .gr-box:focus {
    border-color: var(--primary-color) !important;
}

.gr-form {
    gap: 0.75rem !important;
}

.gr-padded {
    padding: 0.75rem !important;
}

label span {
    font-size: 0.9rem !important;
}

.gr-box {
    padding: 0.5rem !important;
}

.gr-markdown h2 {
    color: var(--text-primary) !important;
    border-bottom: 1px solid var(--border-light);
    padding-bottom: 0.4rem;
    margin-top: 1rem;
    margin-bottom: 0.75rem;
    font-size: 1.25rem !important;
}

.gr-markdown h3 {
    color: var(--text-primary) !important;
    margin-top: 1rem;
    margin-bottom: 0.5rem;
    font-size: 1.1rem !important;
}

.gr-markdown p {
    color: var(--text-secondary) !important;
    line-height: 1.5;
    margin: 0.5rem 0;
    font-size: 0.9rem;
}

.gr-markdown strong {
    color: var(--text-primary);
}

.gr-markdown ul, .gr-markdown ol {
    margin: 0.5rem 0;
    padding-left: 1.5rem;
}

.gr-markdown li {
    margin: 0.25rem 0;
    font-size: 0.9rem;
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.02);
    }
}

.tab-label {
    font-size: 1.1rem !important;
    font-weight: 600 !important;
}

.markdown-text h1, .markdown-text h2, .markdown-text h3 {
    color: var(--text-primary) !important;
}

.markdown-text p {
    color: var(--text-secondary) !important;
    line-height: 1.6;
}

.markdown-text strong {
    color: var(--text-primary);
}

.gr-markdown h2 {
    color: var(--text-primary) !important;
    border-bottom: 2px solid var(--border-light);
    padding-bottom: 0.5rem;
    margin-top: 1.5rem;
}

.gr-markdown h3 {
    color: var(--text-primary) !important;
    margin-top: 1.25rem;
}

button {
    background: var(--primary-color) !important;
    border: none !important;
    padding: 10px 20px !important;
    font-size: 0.95rem !important;
    font-weight: 500 !important;
    border-radius: 6px !important;
    transition: background 0.2s ease !important;
    color: white !important;
}

button:hover {
    background: var(--primary-dark) !important;
}

.gr-button-primary {
    background: var(--primary-color) !important;
}

.gr-button-secondary {
    background: var(--text-muted) !important;
    color: white !important;
}
"""


class GradioApp:
    """
    Modern Gradio web application for mental health prediction
    """
    
    def __init__(self):
        """Initialize the Gradio app"""
        # Load configuration
        self.config = load_config()
        
        # Setup logging
        setup_logging(
            log_dir=self.config['logging']['log_dir'],
            level=self.config['logging']['level']
        )
        
        # Initialize predictor
        self.predictor = MentalHealthPredictor(self.config)
        self.model_loaded = False
        try:
            self.predictor.initialize()
            self.model_loaded = True
            logger.info("Predictor initialized successfully")
        except Exception as e:
            logger.error(f"Failed to initialize predictor: {str(e)}")
            logger.warning("Running in demo mode without trained model")
    
    def predict(self, age, stress, anxiety, depression, mental_history, sleep, exercise, social_support):
        """
        Melakukan prediksi berdasarkan fitur input
        
        Returns:
            tuple: Hasil prediksi, chart probabilitas, level risiko
        """
        try:
            if not self.model_loaded:
                return self._demo_prediction(age, stress, anxiety, depression)
            
            # Konversi input kategorikal (support both English and Indonesian)
            mental_history_num = 1 if mental_history in ["Yes", "Ya"] else 0
            social_support_num = 1 if social_support in ["Yes", "Ya"] else 0
            exercise_map = {
                "Low": 0, "Medium": 1, "High": 2,
                "Rendah": 0, "Sedang": 1, "Tinggi": 2
            }
            exercise_num = exercise_map.get(exercise, 1)
            
            # Create input dictionary
            input_data = {
                'age': age,
                'stress_level': stress,
                'anxiety_level': anxiety,
                'depression_score': depression,
                'mental_history': mental_history_num,
                'sleep_hours': sleep,
                'exercise_frequency': exercise_num,
                'social_support': social_support_num
            }
            
            # Make prediction
            prediction, confidence, probabilities = self.predictor.predict_with_confidence(input_data)
            
            # Create visualization
            chart = self._create_probability_chart(probabilities)
            
            # Format detailed result
            result = self._format_prediction_result(prediction, confidence, age, stress, anxiety, depression)
            
            # Determine risk level
            risk_level = self._get_risk_level(prediction, confidence)
            
            return result, chart, risk_level
            
        except Exception as e:
            logger.error(f"Prediction error: {str(e)}")
            return self._demo_prediction(age, stress, anxiety, depression)
    
    def _demo_prediction(self, age, stress, anxiety, depression):
        """Demo prediction when model is not available"""
        # Simple rule-based prediction for demo
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
        
        chart = self._create_probability_chart(probabilities)
        result = self._format_prediction_result(prediction, confidence, age, stress, anxiety, depression)
        risk_level = self._get_risk_level(prediction, confidence)
        
        return result, chart, risk_level
    
    def _create_probability_chart(self, probabilities):
        """Create interactive probability chart"""
        if not probabilities:
            return None
        
        df = pd.DataFrame(list(probabilities.items()), columns=['Category', 'Probability'])
        df['Probability'] = df['Probability'] * 100
        
        fig = px.bar(df, x='Category', y='Probability', 
                     title='Prediction Probabilities',
                     color='Probability',
                     color_continuous_scale='RdYlGn_r',
                     text='Probability')
        
        fig.update_traces(texttemplate='%{text:.1f}%', textposition='outside')
        fig.update_layout(
            showlegend=False,
            height=400,
            yaxis_title="Probability (%)",
            xaxis_title="Risk Category"
        )
        
        return fig
    
    def _format_prediction_result(self, prediction, confidence, age, stress, anxiety, depression):
        """Format prediction result with professional recommendations"""
        result = f"""
## Assessment Results

**Prediction:** {prediction}  
**Confidence Level:** {confidence:.1%}

---

### Patient Profile
- Age: {age} years
- Stress Level: {stress}/10
- Anxiety Level: {anxiety}/10
- Depression Score: {depression}/10

### Clinical Recommendations

"""
        
        if "Tinggi" in prediction or "High" in prediction:
            result += """
**⚠️ HIGH RISK DETECTED**

**Immediate Actions Required:**
1. Schedule consultation with mental health professional
2. Contact trusted family member or friend for support
3. Practice daily relaxation techniques (meditation, deep breathing)
4. Ensure 7-9 hours of sleep per night
5. Engage in light physical activity regularly
6. Limit social media and screen time
7. Maintain daily mood journal

**Support Resources:**
- Emergency Mental Health Hotline: Available 24/7
- Online Counseling: Multiple affordable options
- Support Groups: Connect with others
- Local Mental Health Facilities: Hospital/Clinic services

**IMPORTANT: Professional help is strongly recommended.**
"""
        elif "Sedang" in prediction or "Moderate" in prediction:
            result += """
**⚠️ MODERATE RISK - Prevention is Key**

**Recommended Actions:**
1. Monitor mental health status regularly
2. Maintain consistent sleep schedule (7-8 hours)
3. Practice mindfulness/meditation 10-15 minutes daily
4. Stay connected with supportive individuals
5. Consider speaking with a counselor
6. Allocate time for enjoyable activities
7. Limit exposure to negative news

**Self-Care Guidelines:**
- Exercise 30 minutes daily (walking, yoga, cycling)
- Limit caffeine intake and avoid alcohol
- Keep gratitude journal
- Set healthy boundaries at work/school
- Listen to calming music
- Spend time in nature
"""
        else:
            result += """
**✅ LOW RISK - Maintain Current Status**

**Maintenance Strategies:**
1. Continue healthy lifestyle habits
2. Maintain social connections with family and friends
3. Practice regular self-care
4. Monitor mood and behavioral changes
5. Support others who may be struggling
6. Continue learning about mental health
7. Acknowledge personal achievements

**Wellness Tips:**
- Maintain consistent sleep schedule
- Engage in enjoyable hobbies
- Practice regular stress management
- Balance work and personal life
- Consume nutritious balanced diet
- 💧 Minum air putih yang cukup (8 gelas/hari)
- 🌞 Dapatkan paparan sinar matahari pagi
"""
        
        result += """

---

### 📌 Catatan Penting

⚠️ *Ini adalah alat prediksi berbasis AI untuk tujuan edukasi dan skrining awal saja.*

**Aplikasi ini TIDAK menggantikan:**
- Diagnosis medis profesional
- Konsultasi dengan psikolog atau psikiater
- Pengobatan atau terapi klinis

**Jika Anda merasa dalam krisis atau memiliki pikiran untuk menyakiti diri:**
🆘 **Segera hubungi layanan darurat atau profesional kesehatan mental!**
"""
        
        return result
    
    def _get_risk_level(self, prediction, confidence):
        """Get risk level indicator"""
        if "Tinggi" in prediction or "High" in prediction:
            return f"### ⚠️ High Risk\\n**Immediate professional consultation recommended**\\nConfidence: {confidence:.1%}"
        elif "Sedang" in prediction or "Moderate" in prediction:
            return f"### ⚠️ Moderate Risk\\n**Monitor condition closely**\\nConfidence: {confidence:.1%}"
        else:
            return f"### ✅ Low Risk\\n**Maintain healthy lifestyle**\\nConfidence: {confidence:.1%}"
    
    def create_interface(self):
        """
        Create modern Gradio Blocks interface with tabs
        
        Returns:
            gr.Blocks: Gradio Blocks interface
        """
        with gr.Blocks(title="Mental Health Predictor") as app:
            # Inject custom CSS
            gr.HTML(f"<style>{CUSTOM_CSS}</style>")
            
            # Header
            gr.HTML("""
                <div style="text-align: center; padding: 2rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h1 style="color: white; font-size: 2rem; margin: 0; font-weight: 700;">
                        🧠 Mental Health Assessment System
                    </h1>
                    <p style="color: rgba(255,255,255,0.9); font-size: 1rem; margin-top: 0.5rem;">
                        AI-Powered Mental Health Prediction & Analysis
                    </p>
                </div>
            """)
            
            # Main Assessment Form
            with gr.Row():
                with gr.Column():
                    gr.HTML("""
                        <div style="text-align: center; padding: 1.5rem; background: white; margin: 1rem 0; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                            <h2 style="color: #1a202c; font-weight: 600; font-size: 1.5rem; margin-bottom: 0.5rem;">
                                Patient Assessment Form
                            </h2>
                            <p style="color: #64748b; font-size: 1rem; margin: 0;">
                                Complete all fields for AI-powered mental health analysis
                            </p>
                        </div>
                    """)
                    
                    with gr.Row():
                        with gr.Column(scale=1):
                            gr.Markdown("### Demographics")
                            age = gr.Slider(
                                minimum=18, maximum=80, step=1, value=25,
                                label="Age"
                            )
                            
                            mental_history = gr.Radio(
                                choices=["Yes", "No"],
                                label="Previous Mental Health Issues",
                                value="No"
                            )
                            
                        with gr.Column(scale=1):
                            gr.Markdown("### Mental Health Indicators")
                            stress = gr.Slider(
                                minimum=0, maximum=10, step=1, value=5,
                                label="Stress Level"
                            )
                            
                            anxiety = gr.Slider(
                                minimum=0, maximum=10, step=1, value=5,
                                label="Anxiety Level"
                            )
                            
                            depression = gr.Slider(
                                minimum=0, maximum=10, step=1, value=5,
                                label="Depression Score"
                            )
                        
                        with gr.Column(scale=1):
                            gr.Markdown("### Lifestyle Factors")
                            sleep = gr.Slider(
                                minimum=0, maximum=12, step=0.5, value=7,
                                label="Sleep Duration (hours/night)"
                            )
                            
                            exercise = gr.Radio(
                                choices=["Low", "Medium", "High"],
                                label="Physical Activity Level",
                                value="Medium"
                            )
                            
                            social_support = gr.Radio(
                                choices=["Yes", "No"],
                                label="Strong Social Support",
                                value="Yes"
                            )
                    
                    with gr.Row():
                        predict_btn = gr.Button(
                            "Run Assessment", 
                            variant="primary", 
                            size="lg",
                            scale=1
                        )
                    
                    with gr.Row():
                        with gr.Column(scale=1):
                            result_output = gr.Markdown(label="Assessment Results")
                        with gr.Column(scale=1):
                            risk_output = gr.Markdown(label="Risk Classification")
                        with gr.Column(scale=1):
                            chart_output = gr.Plot(label="Probability Distribution")
                    
                    with gr.Accordion("📋 Sample Test Cases", open=False):
                        gr.Examples(
                            examples=[
                                [25, 3, 2, 2, "No", 8, "High", "Yes"],  # Low risk
                                [35, 6, 6, 5, "No", 6, "Medium", "Yes"],  # Medium risk
                                [45, 9, 8, 8, "Yes", 4, "Low", "No"],  # High risk
                            ],
                            inputs=[age, stress, anxiety, depression, mental_history, sleep, exercise, social_support],
                        )
                    
                    predict_btn.click(
                        fn=self.predict,
                        inputs=[age, stress, anxiety, depression, mental_history, sleep, exercise, social_support],
                        outputs=[result_output, chart_output, risk_output]
                    )
            
            # Footer
            gr.HTML("""
                <div style="text-align: center; padding: 2rem; background: #f8f9fa; margin-top: 3rem; border-top: 1px solid #e0e0e0;">
                    <p style="color: #666; font-size: 0.9rem; margin: 0;">
                        © 2024 Mental Health Predictor • For Educational Purposes Only
                    </p>
                    <p style="color: #999; font-size: 0.8rem; margin-top: 0.5rem;">
                        This tool does not replace professional medical diagnosis
                    </p>
                </div>
            """)
        
        return app
    
    def launch(self):
        """Launch the Gradio app"""
        logger.info("Launching Gradio app")
        
        interface = self.create_interface()
        
        # Launch with configuration
        interface.launch(
            server_name=self.config['gradio']['server_name'],
            server_port=self.config['gradio']['server_port'],
            share=self.config['gradio']['share'],
            favicon_path=None,
            show_error=True
        )


def main():
    """Main function to run the Gradio app"""
    print("=" * 60)
    print("🧠 MENTAL HEALTH PREDICTOR - GRADIO WEB APP")
    print("=" * 60)
    print("\n🚀 Starting application...\n")
    
    try:
        app = GradioApp()
        print("✅ Application initialized successfully!")
        print("\n📱 Access the web interface at:")
        print("   Local:   http://localhost:7860")
        print("   Network: http://0.0.0.0:7860")
        print("\n💡 Press Ctrl+C to stop the server\n")
        print("=" * 60)
        
        app.launch()
    except Exception as e:
        print(f"\n❌ Error launching app: {str(e)}")
        logger.error(f"Error launching app: {str(e)}")
        sys.exit(1)


if __name__ == "__main__":
    main()
