# 🧠 Mental Health Predictor

An advanced AI-powered mental health assessment tool with **three beautiful web interfaces**: Gradio (modern blocks UI), Flask (Bootstrap 5), and CLI (interactive terminal). This project uses machine learning to predict mental health risk levels and provide personalized recommendations.

## ✨ Features

🎯 **Three Interface Options:**
- **Gradio Web App** - Modern, interactive UI with tabs, charts, and custom styling
- **Flask Web App** - Professional Bootstrap 5 interface with REST API
- **CLI App** - Beautiful terminal interface with colors and interactive prompts

🤖 **AI-Powered:**
- Multiple ML algorithms (Random Forest, Logistic Regression, XGBoost)
- Confidence scores and probability distributions
- Real-time predictions

📊 **Comprehensive Analysis:**
- 8 input features (age, stress, anxiety, depression, etc.)
- Detailed risk assessment (Low/Moderate/High)
- Personalized recommendations
- Interactive visualizations

🔒 **Privacy-Focused:**
- All processing done locally
- No data storage
- Anonymous and confidential

## 📁 Project Structure

```
mental_health_predictor/
├── app/
│   ├── gradio_app.py          # 🎨 Modern Gradio UI with tabs & styling
│   ├── flask_app.py           # 🌐 Flask web app + REST API
│   └── cli_app.py             # 💻 Interactive CLI with colors
├── data/
│   └── data.csv               # 📊 Sample dataset (100 records)
├── src/
│   ├── __init__.py           
│   ├── utils.py               # 🛠️ Helper functions
│   ├── data_preprocess.py     # 🔄 Data preprocessing
│   ├── model_train.py         # 🎓 Model training pipeline
│   └── predictor.py           # 🔮 Prediction module
├── templates/
│   └── index.html             # 🎨 Beautiful Bootstrap 5 UI
├── models/                    # 💾 Trained models (auto-generated)
├── logs/                      # 📝 Application logs
├── results/                   # 📈 Metrics & visualizations
├── config.yaml                # ⚙️ Configuration
├── requirements.txt           # 📦 Dependencies
├── .env                       # 🔐 Environment variables
├── .gitignore
└── README.md
```

## 🚀 Quick Start

### 📦 Installation

1. **Clone or download this repository**
```powershell
cd C:\Users\putri\mental_health_predictor
```

2. **Create and activate virtual environment**
```powershell
python -m venv venv
venv\Scripts\activate
```

3. **Install dependencies**
```powershell
pip install -r requirements.txt
```

### 🎓 Train the Model

Train the AI model using the sample dataset:

```powershell
python src\model_train.py
```

This will:
- ✅ Load and preprocess data
- ✅ Train the model (Random Forest by default)
- ✅ Evaluate performance (~85% accuracy)
- ✅ Save model, scaler, and encoders
- ✅ Generate visualizations (confusion matrix, feature importance)

**Training Output:**
- Models: `models/mental_health_model.pkl`, `scaler.pkl`, `label_encoder.pkl`
- Metrics: `results/metrics.json`
- Charts: `results/confusion_matrix.png`, `results/feature_importance.png`
- Logs: `logs/training.log`

## 🎨 Launch Web Applications

### Option 1: Gradio App (Recommended) ⭐

**Modern UI with tabs, charts, and custom styling**

```powershell
python app\gradio_app.py
```

**Features:**
- 🎨 Beautiful gradient design with custom CSS
- 📑 Multiple tabs (Assessment, Information, Model Info)
- 📊 Interactive Plotly charts
- 💡 Real-time recommendations
- 📱 Responsive design
- 🌐 Accessible at: `http://localhost:7860`

**Screenshot Preview:**
- Header with gradient background
- Slider inputs with live values
- Risk badges with colors (🔴 🟡 🟢)
- Doughnut probability charts
- Personalized recommendations
- Crisis resources section

---

### Option 2: Flask Web App 🌐

**Professional Bootstrap 5 interface with REST API**

```powershell
python app\flask_app.py
```

**Features:**
- 🎯 Professional Bootstrap 5 design
- 📊 Chart.js visualizations
- 🔄 Smooth animations
- 🎨 Gradient cards and buttons
- 📱 Fully responsive
- 🌐 Accessible at: `http://localhost:5000`

**API Endpoints:**
```powershell
POST http://localhost:5000/api/predict
GET  http://localhost:5000/api/health
```

**Example API Request:**
```json
POST /api/predict
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
```

---

### Option 3: CLI Application 💻

**Interactive terminal with colors and beautiful formatting**

```powershell
python app\cli_app.py
```

**Features:**
- 🎨 Colorful terminal UI (ANSI colors)
- ⚡ Interactive prompts with validation
- 📊 ASCII bar charts for probabilities
- 🎯 Step-by-step assessment
- 💡 Detailed recommendations
- 🆘 Crisis resources

**CLI Experience:**
```
==================================================================
🧠 MENTAL HEALTH PREDICTOR - CLI
==================================================================

What would you like to do?
  1. Take mental health assessment
  2. View information
  3. Exit

Choice (1-3): 1
```

## 📊 Model Performance & Configuration

### Expected Performance Metrics

After training on the sample dataset:
- **Accuracy**: ~85%
- **Precision**: ~83%
- **Recall**: ~82%
- **F1-Score**: ~82%

### Output Files

```
mental_health_predictor/
├── models/
│   ├── mental_health_model.pkl    # Trained model
│   ├── scaler.pkl                 # Feature scaler
│   └── label_encoder.pkl          # Label encoder
├── results/
│   ├── metrics.json              # Performance metrics
│   ├── confusion_matrix.png      # Confusion matrix plot
│   └── feature_importance.png    # Feature importance plot
└── logs/
    └── training.log              # Training logs
```

### Configuration (`config.yaml`)

```yaml
# Model Algorithm
model:
  algorithm: "random_forest"  # Options: random_forest, logistic_regression, xgboost

# Random Forest Parameters
random_forest:
  n_estimators: 100
  max_depth: 10
  random_state: 42

# Data Configuration
data:
  test_size: 0.2
  random_state: 42

# Gradio App
gradio:
  server_port: 7860
  share: false  # Set true for public URL
```

## 🎨 UI Customization

### Gradio App Styling

Edit `app/gradio_app.py` to customize:
- **Colors**: Modify `CUSTOM_CSS` gradient colors
- **Tabs**: Add/remove tabs in `create_interface()`
- **Inputs**: Adjust slider ranges and labels
- **Theme**: Change `gr.themes.Soft()` to other themes

### Flask App Styling

Edit `templates/index.html` to customize:
- **Colors**: Modify CSS variables in `<style>`
- **Layout**: Adjust Bootstrap grid classes
- **Charts**: Customize Chart.js configuration
- **Animations**: Add/modify CSS transitions

### CLI Colors

Edit `app/cli_app.py` to customize:
- **Colors**: Modify `Colors` class ANSI codes
- **Prompts**: Adjust input messages
- **Layout**: Change section headers and formatting

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## ⚠️ Disclaimer

This application is for educational and research purposes only. It should not be used as a substitute for professional mental health advice, diagnosis, or treatment. If you're experiencing mental health issues, please consult with a qualified healthcare professional.

## 📄 License

This project is open source and available under the MIT License.

## � Authors

- Your Name

## 🙏 Acknowledgments

- Thanks to all contributors
- Data source: [Mention your data source]
- Built with scikit-learn, Gradio, and Python

## � Contact

For questions or feedback, please contact [your-email@example.com]

---

**Note**: Remember to update the feature names and input fields in `app/gradio_app.py` based on your actual dataset columns before running the application.

##  Advanced Usage

### Python API

```python
from src.utils import load_config
from src.predictor import MentalHealthPredictor

config = load_config()
predictor = MentalHealthPredictor(config)
predictor.initialize()

input_data = {'age': 25, 'stress_level': 5, 'anxiety_level': 5, 
              'depression_score': 5, 'mental_history': 0, 
              'sleep_hours': 7, 'exercise_frequency': 1, 'social_support': 1}

prediction, confidence, probabilities = predictor.predict_with_confidence(input_data)
print(f"Prediction: {prediction} ({confidence:.2%})")
```

### Try Different Algorithms

Edit config.yaml, change algorithm to xgboost or logistic_regression, then retrain.

##  Deployment

### Gradio (Hugging Face)
Upload to Hugging Face Spaces with Gradio SDK for instant public URL!

### Flask (Render/Heroku)
Create Procfile with: web: python app/flask_app.py

### REST API Usage
```bash
curl -X POST http://localhost:5000/api/predict -H "Content-Type: application/json" -d '{"age":25,"stress_level":5,"anxiety_level":5,"depression_score":5,"mental_history":0,"sleep_hours":7,"exercise_frequency":1,"social_support":1}'
```

##  Crisis Resources (24/7)

- National Suicide Prevention Lifeline: 1-800-273-8255
- Crisis Text Line: Text HOME to 741741
- International: https://www.iasp.info/resources/Crisis_Centres/

---

Made with  for Mental Health Awareness | MIT License
"# mental_health_predictor" 
