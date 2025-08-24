#!/usr/bin/env python3
"""
Script untuk menjalankan aplikasi web Excel Preview & PDF Converter
"""

import os
import sys
import subprocess
import webbrowser
import time
import threading

def check_dependencies():
    """Check if required dependencies are installed"""
    required_packages = ['flask', 'openpyxl', 'pywin32']
    missing_packages = []
    
    for package in required_packages:
        try:
            __import__(package)
        except ImportError:
            missing_packages.append(package)
    
    if missing_packages:
        print("❌ Missing dependencies:")
        for package in missing_packages:
            print(f"   - {package}")
        print("\n📦 Installing dependencies...")
        
        try:
            subprocess.check_call([sys.executable, '-m', 'pip', 'install', '-r', 'requirements_web.txt'])
            print("✅ Dependencies installed successfully!")
        except subprocess.CalledProcessError:
            print("❌ Failed to install dependencies")
            print("Please run: pip install -r requirements_web.txt")
            return False
    
    return True

def create_test_file():
    """Create a test Excel file if it doesn't exist"""
    try:
        from test_excel_converter import create_test_excel
        test_file = create_test_excel()
        print(f"📄 Test file created: {test_file}")
        return test_file
    except Exception as e:
        print(f"⚠️ Could not create test file: {e}")
        return None

def open_browser():
    """Open browser after a short delay"""
    time.sleep(2)
    try:
        webbrowser.open('http://localhost:5000')
        print("🌐 Browser opened automatically")
    except:
        print("🌐 Please open your browser and go to: http://localhost:5000")

def main():
    print("=" * 60)
    print("🌐 Excel Preview & PDF Converter - Web Version")
    print("=" * 60)
    
    # Check dependencies
    if not check_dependencies():
        return
    
    # Create test file
    test_file = create_test_file()
    
    # Create uploads directory
    os.makedirs('uploads', exist_ok=True)
    
    print("\n🚀 Starting web application...")
    print("📱 The application will open in your browser")
    print("🔧 Press Ctrl+C to stop the server")
    print("-" * 60)
    
    # Start browser in background
    browser_thread = threading.Thread(target=open_browser)
    browser_thread.daemon = True
    browser_thread.start()
    
    try:
        # Import and run Flask app
        from web_excel_preview import app
        
        print("✅ Server started successfully!")
        print("🌐 URL: http://localhost:5000")
        print("📁 Upload folder: ./uploads/")
        
        if test_file:
            print(f"📄 Test file available: {test_file}")
        
        print("\n" + "=" * 60)
        app.run(debug=False, host='0.0.0.0', port=5000)
        
    except KeyboardInterrupt:
        print("\n\n🛑 Server stopped by user")
    except Exception as e:
        print(f"\n❌ Error starting server: {e}")
        print("Please check if port 5000 is available")

if __name__ == "__main__":
    main()