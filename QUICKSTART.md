# 🚀 QUICK START GUIDE

## Mental Health Predictor - Complete Setup

### ⚡ Fast Setup (5 minutes)

1. **Install Dependencies**
```powershell
pip install -r requirements.txt
```

2. **Train the Model**
```powershell
python src\model_train.py
```

3. **Choose Your Interface:**

**Option A: Gradio (Recommended)**
```powershell
python app\gradio_app.py
```
Open: http://localhost:7860

**Option B: Flask**
```powershell
python app\flask_app.py
```
Open: http://localhost:5000

**Option C: CLI**
```powershell
python app\cli_app.py
```

---

## 📁 What You Have

### Three Beautiful Web Interfaces:

1. **Gradio App** (`app/gradio_app.py`)
   - Modern UI with gradient design
   - Multiple tabs (Assessment, Info, Model Info)
   - Interactive Plotly charts
   - Custom CSS styling
   - Real-time recommendations

2. **Flask App** (`app/flask_app.py`)
   - Professional Bootstrap 5 design
   - REST API endpoints
   - Chart.js visualizations
   - Responsive mobile-first design
   - Complete CRUD operations

3. **CLI App** (`app/cli_app.py`)
   - Colorful terminal interface
   - Interactive prompts
   - ASCII bar charts
   - Step-by-step assessment

### Sample Data
- `data/Data.csv` - 100 sample records
- Features: age, stress, anxiety, depression, etc.
- 3 risk categories: Low, Moderate, High

### Complete ML Pipeline
- `src/data_preprocess.py` - Data cleaning & encoding
- `src/model_train.py` - Model training & evaluation
- `src/predictor.py` - Prediction engine
- `src/utils.py` - Helper functions

---

## 🎨 UI Features

### Gradio Interface
✅ Gradient header with brain icon
✅ Interactive sliders with live values
✅ Risk badges with color coding (🔴🟡🟢)
✅ Doughnut probability charts
✅ Personalized recommendation cards
✅ Crisis resources section
✅ Multiple example cases

### Flask Interface
✅ Animated gradient cards
✅ Smooth range sliders
✅ Chart.js doughnut visualization
✅ Feature cards with hover effects
✅ Responsive for mobile devices
✅ REST API documentation
✅ Health check endpoint

### CLI Interface
✅ ANSI color support
✅ Input validation
✅ Progress indicators
✅ ASCII bar charts
✅ Color-coded risk levels
✅ Detailed step-by-step flow

---

## 📊 Expected Results

After training:
- **Accuracy**: ~85%
- **Training Time**: ~30 seconds
- **Model Size**: ~1-5 MB

Generated files:
- `models/mental_health_model.pkl`
- `models/scaler.pkl`
- `models/label_encoder.pkl`
- `results/metrics.json`
- `results/confusion_matrix.png`
- `results/feature_importance.png`

---

## 🔧 Customization Ideas

### Change Colors (Gradio)
Edit `app/gradio_app.py`, line ~20 (CUSTOM_CSS):
```python
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
```

### Change Colors (Flask)
Edit `templates/index.html`, line ~30 (CSS variables):
```css
--primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
```

### Add New Features
1. Edit `data/data.csv` - add columns
2. Update `app/gradio_app.py` - add inputs
3. Update `app/flask_app.py` - add form fields
4. Retrain model

### Try Different Algorithms
Edit `config.yaml`:
```yaml
model:
  algorithm: "xgboost"  # or "logistic_regression"
```

---

## 🆘 Troubleshooting

### Model Not Found Error
**Solution**: Run `python src\model_train.py` first

### Import Error
**Solution**: Install dependencies `pip install -r requirements.txt`

### Port Already in Use
**Gradio**: Edit `config.yaml`, change `server_port: 7860` to another port
**Flask**: App uses port 5000 by default

### Demo Mode Warning
**Meaning**: Model not trained yet, using rule-based predictions
**Solution**: Train the model to get AI predictions

---

## 📱 Access URLs

| Interface | URL | Port |
|-----------|-----|------|
| Gradio | http://localhost:7860 | 7860 |
| Flask | http://localhost:5000 | 5000 |
| API | http://localhost:5000/api | 5000 |

---

## 🎯 Test Cases

Try these in any interface:

**Low Risk Case:**
- Age: 25, Stress: 2, Anxiety: 2, Depression: 2
- Sleep: 8, Exercise: High, Social Support: Yes

**Moderate Risk Case:**
- Age: 35, Stress: 6, Anxiety: 6, Depression: 5
- Sleep: 6, Exercise: Medium, Social Support: Yes

**High Risk Case:**
- Age: 45, Stress: 9, Anxiety: 8, Depression: 8
- Sleep: 4, Exercise: Low, Social Support: No

---

## 🚀 Next Steps

1. ✅ Train model with your own data
2. ✅ Customize UI colors and styling
3. ✅ Deploy to Hugging Face (Gradio) or Render (Flask)
4. ✅ Add authentication for production
5. ✅ Integrate with database for tracking
6. ✅ Add email notifications
7. ✅ Build mobile app version

---

**Need Help?**
- Check `README.md` for full documentation
- Check `logs/` directory for error logs
- Verify all dependencies installed
- Ensure Python 3.8+ is installed

---

**Made with ❤️ for Mental Health Awareness**
