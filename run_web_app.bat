@echo off
echo ========================================
echo Excel Preview & PDF Converter - Web
echo ========================================
echo.

echo Installing web dependencies...
pip install -r requirements_web.txt

echo.
echo Creating test Excel file...
python test_excel_converter.py

echo.
echo Starting web application...
python run_web_app.py

pause