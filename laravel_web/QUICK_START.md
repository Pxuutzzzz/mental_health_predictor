# Laravel + Bootstrap - Quick Start Guide

## 🚀 Quick Start (2 Steps)

### Step 1: Navigate to Laravel Web Directory
```bash
cd c:\Users\putri\mental_health_predictor\laravel_web
```

### Step 2: Start PHP Server
```bash
php -S localhost:8000
```

### Step 3: Open Browser
Go to: **http://localhost:8000**

---

## 📋 What You Get

### ✨ Main Features
- **Beautiful Bootstrap 5 UI** with gradient headers
- **Real-time form validation** and value display
- **AJAX predictions** without page reload
- **Session-based history** tracking
- **Mobile responsive** design
- **Professional animations** and transitions

### 📄 Pages Available
1. **Assessment Form** (`/` or `/assessment`)
   - Interactive form with sliders and buttons
   - Real-time value updates
   - Animated results display
   
2. **History Page** (`/history`)
   - View all past assessments
   - Clear history option
   - Detailed input/output display

---

## 🎨 UI Features

### Form Components (Bootstrap 5)
- Range sliders with live value display
- Button groups for Yes/No selections
- Styled select dropdowns
- Gradient color scheme
- Box shadows and hover effects
- Loading spinner during prediction
- Animated result cards

### Color Scheme
- **Primary**: Purple gradient (`#667eea` → `#764ba2`)
- **Success**: Green for low risk
- **Warning**: Yellow for moderate risk
- **Danger**: Red for high risk

---

## 🔧 Technical Details

### Stack
- **Frontend**: Bootstrap 5.3.0 + Bootstrap Icons
- **Backend**: PHP 8.x (Laravel-style MVC)
- **ML Model**: Python (existing predictor)
- **Session**: PHP native sessions

### Architecture
```
Browser (Bootstrap UI)
    ↓ AJAX POST
PHP Controller
    ↓ exec()
Python ML Model
    ↓ JSON
PHP Controller
    ↓ JSON Response
Browser (Display Results)
```

---

## 📁 File Structure
```
laravel_web/
├── index.php                    # Router
├── app/Controllers/
│   └── PredictionController.php # Prediction logic
├── views/
│   ├── assessment.php           # Main form (Bootstrap)
│   └── history.php              # History viewer
└── README.md                    # Documentation
```

---

## 🎯 Usage Example

1. **Fill the form**:
   - Adjust age slider (18-80)
   - Set stress, anxiety, depression levels (0-10)
   - Choose mental health history (Yes/No)
   - Set sleep hours (0-12)
   - Select exercise level (Low/Medium/High)
   - Choose social support (Yes/No)

2. **Click "Run Assessment"**:
   - Form data sent via AJAX
   - PHP calls Python ML model
   - Results displayed instantly
   - Saved to session history

3. **View history** (optional):
   - Click "History" in navbar
   - See all past assessments
   - Clear history if needed

---

## ⚙️ Configuration

### Change Python Path
Edit `laravel_web/app/Controllers/PredictionController.php`:
```php
$this->pythonPath = 'YOUR_PYTHON_PATH';
```

### Change Port
```bash
php -S localhost:9000  # Use port 9000 instead
```

### Enable Debug Mode
Add to `index.php`:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

---

## 🆚 Comparison: Gradio vs Laravel+Bootstrap

| Aspect | Gradio | Laravel+Bootstrap |
|--------|--------|-------------------|
| **Setup** | `python app/gradio_app.py` | `php -S localhost:8000` |
| **UI** | Auto-generated | Fully customizable |
| **Styling** | Limited CSS | Full Bootstrap control |
| **History** | Not built-in | Session-based ✅ |
| **Deployment** | Python server | Any PHP hosting |
| **Database** | Requires setup | Easy integration |
| **Auth** | Manual | Laravel built-in |

---

## 🌟 Next Steps

Want to enhance? Add:
- MySQL database for persistent history
- User authentication system
- PDF report generation
- Email notifications
- Chart.js visualizations
- Admin dashboard
- API for mobile apps

---

## ❗ Troubleshooting

**"No predictions showing"**
→ Check Python path in PredictionController.php

**"Session not working"**
→ Ensure `session_start()` is called in index.php

**"Port already in use"**
→ Use different port: `php -S localhost:8001`

---

## 📞 Support

Issues? Check:
1. PHP version: `php -v` (need 7.4+)
2. Python accessible: `python --version`
3. Model files exist in `/models` directory

---

**Ready to use! 🎉**

Open: http://localhost:8000
