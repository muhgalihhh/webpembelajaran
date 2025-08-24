#!/usr/bin/env python3
"""
Script untuk testing API endpoints Excel Preview
"""

import requests
import json
import os
import time

class ExcelPreviewAPITester:
    def __init__(self, base_url="http://localhost:8000"):
        self.base_url = base_url
        self.session = requests.Session()
        self.session.headers.update({
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        })
    
    def test_upload_file(self, file_path):
        """Test upload Excel file"""
        print(f"📤 Testing file upload: {file_path}")
        
        if not os.path.exists(file_path):
            print(f"❌ File not found: {file_path}")
            return None
        
        url = f"{self.base_url}/excel-preview/upload"
        
        try:
            with open(file_path, 'rb') as f:
                files = {'excel_file': f}
                response = self.session.post(url, files=files)
            
            if response.status_code == 200:
                result = response.json()
                if result.get('success'):
                    print(f"✅ Upload successful!")
                    print(f"   File path: {result.get('file_path')}")
                    print(f"   Sheet names: {result.get('sheet_names')}")
                    return result
                else:
                    print(f"❌ Upload failed: {result.get('message')}")
                    return None
            else:
                print(f"❌ HTTP Error: {response.status_code}")
                print(f"   Response: {response.text}")
                return None
                
        except Exception as e:
            print(f"❌ Error during upload: {e}")
            return None
    
    def test_preview(self, file_path, sheet_name=None, range_cell=None):
        """Test Excel preview"""
        print(f"👁️  Testing Excel preview")
        print(f"   File: {file_path}")
        print(f"   Sheet: {sheet_name or 'Default'}")
        print(f"   Range: {range_cell or 'Default'}")
        
        url = f"{self.base_url}/excel-preview/preview"
        
        data = {
            'file_path': file_path,
            'sheet_name': sheet_name,
            'range': range_cell
        }
        
        try:
            response = self.session.post(url, json=data)
            
            if response.status_code == 200:
                result = response.json()
                if result.get('success'):
                    print(f"✅ Preview successful!")
                    print(f"   Sheet name: {result.get('sheet_name')}")
                    print(f"   Range: {result.get('range')}")
                    print(f"   Data rows: {len(result.get('data', []))}")
                    print(f"   CSS rules: {len(result.get('css', {}))}")
                    return result
                else:
                    print(f"❌ Preview failed: {result.get('message')}")
                    return None
            else:
                print(f"❌ HTTP Error: {response.status_code}")
                print(f"   Response: {response.text}")
                return None
                
        except Exception as e:
            print(f"❌ Error during preview: {e}")
            return None
    
    def test_download(self, file_name):
        """Test file download"""
        print(f"📥 Testing file download: {file_name}")
        
        url = f"{self.base_url}/excel-preview/download/{file_name}"
        
        try:
            response = self.session.get(url)
            
            if response.status_code == 200:
                print(f"✅ Download successful!")
                print(f"   File size: {len(response.content)} bytes")
                return True
            else:
                print(f"❌ HTTP Error: {response.status_code}")
                return False
                
        except Exception as e:
            print(f"❌ Error during download: {e}")
            return False
    
    def test_delete(self, file_name):
        """Test file deletion"""
        print(f"🗑️  Testing file deletion: {file_name}")
        
        url = f"{self.base_url}/excel-preview/delete/{file_name}"
        
        try:
            response = self.session.delete(url)
            
            if response.status_code == 200:
                result = response.json()
                if result.get('success'):
                    print(f"✅ Deletion successful!")
                    return True
                else:
                    print(f"❌ Deletion failed: {result.get('message')}")
                    return False
            else:
                print(f"❌ HTTP Error: {response.status_code}")
                return False
                
        except Exception as e:
            print(f"❌ Error during deletion: {e}")
            return False
    
    def run_full_test(self, file_path):
        """Run complete test suite"""
        print("🧪 Running Excel Preview API Test Suite")
        print("=" * 50)
        
        # Test 1: Upload file
        upload_result = self.test_upload_file(file_path)
        if not upload_result:
            print("❌ Upload test failed. Stopping tests.")
            return False
        
        file_path_stored = upload_result.get('file_path')
        file_name = upload_result.get('file_name')
        sheet_names = upload_result.get('sheet_names', [])
        
        print()
        
        # Test 2: Preview with default settings
        preview_result = self.test_preview(file_path_stored)
        if not preview_result:
            print("❌ Preview test failed.")
        
        print()
        
        # Test 3: Preview with specific sheet
        if sheet_names:
            preview_result2 = self.test_preview(file_path_stored, sheet_names[0])
            if not preview_result2:
                print("❌ Sheet-specific preview test failed.")
        
        print()
        
        # Test 4: Preview with range
        preview_result3 = self.test_preview(file_path_stored, None, "A1:C5")
        if not preview_result3:
            print("❌ Range-specific preview test failed.")
        
        print()
        
        # Test 5: Download file
        download_success = self.test_download(file_name)
        
        print()
        
        # Test 6: Delete file
        delete_success = self.test_delete(file_name)
        
        print()
        print("=" * 50)
        print("🏁 Test suite completed!")
        
        return True

def main():
    # Check if sample file exists
    sample_file = "sample_excel_with_styles.xlsx"
    
    if not os.path.exists(sample_file):
        print(f"❌ Sample file not found: {sample_file}")
        print("Please run create_sample_excel.py first to create a test file.")
        return
    
    # Create tester instance
    tester = ExcelPreviewAPITester()
    
    # Run full test suite
    success = tester.run_full_test(sample_file)
    
    if success:
        print("✅ All tests completed successfully!")
    else:
        print("❌ Some tests failed. Check the output above.")

if __name__ == "__main__":
    main()