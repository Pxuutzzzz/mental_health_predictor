@echo off
echo ==========================================
echo   Mental Health Predictor - Local Runner
echo ==========================================

echo [1/3] Activating Python Virtual Environment...
if exist "venv\Scripts\activate.bat" (
    call venv\Scripts\activate.bat
) else (
    echo [INFO] venv not found. Creating it...
    python -m venv venv
    call venv\Scripts\activate.bat
)

echo [2/3] Checking dependencies...
pip install -r requirements.txt > nul 2>&1
rem Install Composer dependencies if vendor missing
if not exist "vendor" (
    echo [INFO] Installing Composer dependencies...
    call composer install
)

echo [3/3] Starting PHP Development Server...
echo.
echo Open your browser at: http://localhost:8000
echo Press Ctrl+C to stop.
echo.

php -S localhost:8000 router.php

pause
