#!/bin/bash

echo "🚀 Starting Excel Preview System..."
echo "=================================="

# Check if PHP is available
if ! command -v php &> /dev/null; then
    echo "❌ PHP is not installed or not in PATH"
    echo "Please install PHP and try again"
    exit 1
fi

# Check if Composer is available
if ! command -v composer &> /dev/null; then
    echo "⚠️  Composer not found, trying to use composer.phar..."
    if [ -f "composer.phar" ]; then
        COMPOSER_CMD="php composer.phar"
    else
        echo "❌ Composer not found. Please install Composer first."
        exit 1
    fi
else
    COMPOSER_CMD="composer"
fi

# Install dependencies if needed
echo "📦 Installing dependencies..."
$COMPOSER_CMD install --no-dev --optimize-autoloader

# Create storage directories
echo "📁 Creating storage directories..."
mkdir -p storage/app/public/excel-files
chmod -R 775 storage

# Create symbolic link for storage
echo "🔗 Creating storage symbolic link..."
php artisan storage:link

# Generate application key if not exists
if [ ! -f ".env" ]; then
    echo "📝 Creating .env file..."
    cp .env.example .env
    php artisan key:generate
fi

# Clear caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Create sample Excel file if Python is available
if command -v python3 &> /dev/null; then
    echo "🐍 Creating sample Excel file..."
    python3 create_sample_excel.py
elif command -v python &> /dev/null; then
    echo "🐍 Creating sample Excel file..."
    python create_sample_excel.py
else
    echo "⚠️  Python not found. Sample Excel file will not be created."
    echo "You can manually create an Excel file for testing."
fi

echo ""
echo "✅ Setup completed!"
echo ""
echo "🌐 Access the application:"
echo "   - Main page: http://localhost:8000"
echo "   - Excel Preview: http://localhost:8000/excel-preview"
echo ""
echo "📋 Available routes:"
echo "   - GET  /excel-preview          - Excel Preview interface"
echo "   - POST /excel-preview/upload   - Upload Excel file"
echo "   - POST /excel-preview/preview  - Load Excel preview"
echo "   - GET  /excel-preview/download/{fileName} - Download file"
echo "   - DELETE /excel-preview/delete/{fileName} - Delete file"
echo ""
echo "🚀 Starting development server..."
echo "Press Ctrl+C to stop the server"
echo ""

# Start development server
php artisan serve --host=0.0.0.0 --port=8000