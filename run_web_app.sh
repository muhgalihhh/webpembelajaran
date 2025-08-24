#!/bin/bash

echo "========================================"
echo "Excel Preview & PDF Converter - Web"
echo "========================================"
echo

echo "Installing web dependencies..."
pip3 install -r requirements_web.txt

echo
echo "Creating test Excel file..."
python3 test_excel_converter.py

echo
echo "Starting web application..."
python3 run_web_app.py