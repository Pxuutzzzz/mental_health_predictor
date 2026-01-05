# Mental Health Predictor - Laravel + Bootstrap Version

## Overview
This is a Laravel-style implementation of the Mental Health Predictor using Bootstrap 5 for the frontend UI. It integrates with the existing Python ML model and now supports **hospital integration** for direct referral to partner healthcare facilities.

## Features
✅ Professional Bootstrap 5 UI
✅ Responsive design (mobile-friendly)
✅ Real-time form validation
✅ AJAX-based predictions
✅ Session-based history tracking
✅ Professional gradients and animations
✅ Bootstrap Icons integration
✅ **Hospital Integration** – Direct referrals to partner hospitals/RS
✅ Encrypted data transmission (HTTPS + Bearer Token)
✅ Audit logging & compliance (HIPAA/GDPR)

## File Structure
```
laravel_web/
├── index.php                           # Main entry point (router)
├── app/
│   └── Controllers/
│       └── PredictionController.php    # Handles prediction logic
├── views/
│   ├── assessment.php                  # Main assessment form
│   └── history.php                     # Assessment history page
└── README.md                           # This file
```

## Setup Instructions

### 1. Requirements
- PHP 7.4+ (built-in web server or Apache/Nginx)
- Python 3.x with required packages
- Existing ML model files in `/models` directory

### 2. Running with PHP Built-in Server

**Option A: From laravel_web directory:**
```bash
cd laravel_web
php -S localhost:9000
```

**Option B: From project root:**
```bash
php -S localhost:9000 -t laravel_web
```

### 3. Access the Application
Open your browser and navigate to:
- Main Assessment: `http://localhost:9000`
- History: `http://localhost:9000/history`

## How It Works

### Frontend (Bootstrap 5)
- **assessment.php**: Patient assessment form with:
  - Range sliders for numerical inputs (Age, Stress, Anxiety, Depression, Sleep)
  - Radio buttons for binary choices (Mental History, Social Support)
  - Dropdown for exercise level
  - Real-time value display
  - AJAX form submission
  - Animated results display

- **history.php**: Session-based history viewer showing:
  - All previous assessments
  - Input parameters and results
  - Timestamp for each assessment
  - Clear history option

### Backend (PHP Laravel-style)
- **index.php**: Simple router handling:
  - `/` or `/assessment` → Main form
  - `/predict` (POST) → Prediction endpoint
  - `/history` → History viewer

- **PredictionController.php**: 
  - Receives form data
  - Executes Python predictor script
  - Parses results
  - Saves to session
  - Returns JSON response

### Integration with Python ML
The controller calls the Python predictor using:
```php
exec('python predictor.py predict --age 25 --stress 5 ...')
```

## Customization

### Changing Colors
Edit the CSS variables in `assessment.php`:
```css
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
```

### Modifying Form Fields
Edit the form section in `views/assessment.php` to add/remove fields.

### Adjusting Python Path
Update the Python path in `PredictionController.php`:
```php
$this->pythonPath = 'C:\Users\putri\anaconda3\python.exe';
```

## Features Comparison

| Feature | Gradio Version | Laravel + Bootstrap |
|---------|---------------|---------------------|
| UI Framework | Gradio | Bootstrap 5 |
| Backend | Python | PHP |
| Styling | Custom CSS | Bootstrap Components |
| Form Handling | Gradio Components | HTML5 + JavaScript |
| Session Management | None | PHP Sessions |
| History Tracking | None | Built-in |
| Mobile Responsive | Yes | Yes |
| Customization | Limited | Full Control |

## Advantages of Laravel + Bootstrap

✅ **Full Control**: Complete customization of UI/UX
✅ **Professional**: Bootstrap components look polished
✅ **Familiar**: Standard web technologies (HTML/CSS/JS)
✅ **Database Ready**: Easy to add MySQL integration
✅ **Authentication**: Can add user login easily
✅ **Production Ready**: Can deploy to any PHP hosting
✅ **SEO Friendly**: Better for search engine optimization
✅ **Hospital Integration**: Built-in referral system to healthcare partners

## Future Enhancements

Possible additions:
- [ ] MySQL database integration
- [ ] User authentication (login/register)
- [ ] PDF report generation
- [ ] Email notifications
- [ ] Multi-language support
- [ ] Admin dashboard
- [ ] Export history to CSV
- [ ] Chart.js visualization
- [ ] API endpoints for mobile apps
- [x] **Hospital integration (COMPLETED)** – See [HOSPITAL_INTEGRATION.md](HOSPITAL_INTEGRATION.md)

## Troubleshooting

**Problem**: Python not found
- **Solution**: Update `$this->pythonPath` in PredictionController.php

**Problem**: Predictions not working
- **Solution**: Check that Python model files exist in `/models` directory

**Problem**: Session history not persisting
- **Solution**: Ensure PHP sessions are enabled in php.ini

## License
Educational purposes only. Not for production medical use.

## Support
For issues or questions, refer to the main project documentation.
