#!/bin/bash

echo "========================================"
echo "Excel Preview & PDF Converter"
echo "========================================"
echo

echo "Installing dependencies..."
pip3 install -r requirements_simple.txt

echo
echo "Creating test Excel file..."
python3 test_excel_converter.py

echo
echo "Starting the application..."
python3 excel_preview_converter_simple.py