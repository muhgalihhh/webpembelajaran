@echo off
echo ========================================
echo Excel Preview & PDF Converter
echo ========================================
echo.

echo Installing dependencies...
pip install -r requirements_simple.txt

echo.
echo Creating test Excel file...
python test_excel_converter.py

echo.
echo Starting the application...
python excel_preview_converter_simple.py

pause