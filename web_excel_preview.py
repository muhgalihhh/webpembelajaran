#!/usr/bin/env python3
"""
Web-based Excel Preview & PDF Converter
Menggunakan Flask untuk menampilkan Excel dengan format asli
"""

from flask import Flask, render_template, request, jsonify, send_file, redirect, url_for
import openpyxl
from openpyxl.styles import Font, Alignment, Border, Side, PatternFill, Color
from openpyxl.utils import get_column_letter
import os
import tempfile
import json
from werkzeug.utils import secure_filename
import win32com.client
import threading
import time

app = Flask(__name__)
app.config['SECRET_KEY'] = 'excel_preview_secret_key'
app.config['UPLOAD_FOLDER'] = 'uploads'
app.config['MAX_CONTENT_LENGTH'] = 16 * 1024 * 1024  # 16MB max file size

# Buat folder upload jika belum ada
os.makedirs(app.config['UPLOAD_FOLDER'], exist_ok=True)

class ExcelProcessor:
    def __init__(self):
        self.workbook = None
        self.filename = None
        
    def load_workbook(self, file_path):
        """Load Excel workbook"""
        try:
            self.workbook = openpyxl.load_workbook(file_path, data_only=False)
            self.filename = os.path.basename(file_path)
            return True
        except Exception as e:
            print(f"Error loading workbook: {e}")
            return False
    
    def get_sheet_names(self):
        """Get list of sheet names"""
        if self.workbook:
            return self.workbook.sheetnames
        return []
    
    def get_sheet_data(self, sheet_name, start_row=1, end_row=50, start_col=1, end_col=26):
        """Get sheet data with formatting"""
        if not self.workbook or sheet_name not in self.workbook.sheetnames:
            return None
        
        worksheet = self.workbook[sheet_name]
        data = []
        
        for row in range(start_row, min(end_row + 1, worksheet.max_row + 1)):
            row_data = []
            for col in range(start_col, min(end_col + 1, worksheet.max_column + 1)):
                cell = worksheet.cell(row=row, column=col)
                cell_info = self.get_cell_info(cell)
                row_data.append(cell_info)
            data.append(row_data)
        
        return {
            'data': data,
            'max_row': worksheet.max_row,
            'max_col': worksheet.max_column,
            'sheet_name': sheet_name
        }
    
    def get_cell_info(self, cell):
        """Extract cell information including formatting"""
        cell_info = {
            'value': cell.value,
            'row': cell.row,
            'column': cell.column,
            'coordinate': cell.coordinate,
            'styles': {}
        }
        
        # Font information
        if cell.font:
            cell_info['styles']['font'] = {
                'name': cell.font.name,
                'size': cell.font.size,
                'bold': cell.font.bold,
                'italic': cell.font.italic,
                'underline': cell.font.underline,
                'color': str(cell.font.color.rgb) if cell.font.color and cell.font.color.rgb else None
            }
        
        # Alignment
        if cell.alignment:
            cell_info['styles']['alignment'] = {
                'horizontal': cell.alignment.horizontal,
                'vertical': cell.alignment.vertical,
                'wrap_text': cell.alignment.wrap_text,
                'text_rotation': cell.alignment.text_rotation
            }
        
        # Border
        if cell.border:
            borders = {}
            for side in ['left', 'right', 'top', 'bottom']:
                border_side = getattr(cell.border, side)
                if border_side:
                    borders[side] = {
                        'style': border_side.style,
                        'color': str(border_side.color.rgb) if border_side.color and border_side.color.rgb else None
                    }
            if borders:
                cell_info['styles']['border'] = borders
        
        # Fill (background color)
        if cell.fill:
            if cell.fill.start_color and cell.fill.start_color.rgb:
                cell_info['styles']['fill'] = {
                    'color': str(cell.fill.start_color.rgb)
                }
        
        # Number format
        if cell.number_format:
            cell_info['styles']['number_format'] = cell.number_format
        
        return cell_info
    
    def get_column_widths(self, sheet_name):
        """Get column widths"""
        if not self.workbook or sheet_name not in self.workbook.sheetnames:
            return {}
        
        worksheet = self.workbook[sheet_name]
        widths = {}
        
        for col in range(1, worksheet.max_column + 1):
            column_letter = get_column_letter(col)
            width = worksheet.column_dimensions[column_letter].width
            if width:
                widths[col] = width
        
        return widths
    
    def get_row_heights(self, sheet_name):
        """Get row heights"""
        if not self.workbook or sheet_name not in self.workbook.sheetnames:
            return {}
        
        worksheet = self.workbook[sheet_name]
        heights = {}
        
        for row in range(1, worksheet.max_row + 1):
            height = worksheet.row_dimensions[row].height
            if height:
                heights[row] = height
        
        return heights

# Global Excel processor instance
excel_processor = ExcelProcessor()

@app.route('/')
def index():
    """Main page"""
    return render_template('index.html')

@app.route('/upload', methods=['POST'])
def upload_file():
    """Handle file upload"""
    if 'file' not in request.files:
        return jsonify({'error': 'No file uploaded'}), 400
    
    file = request.files['file']
    if file.filename == '':
        return jsonify({'error': 'No file selected'}), 400
    
    if file and file.filename.lower().endswith(('.xlsx', '.xls')):
        filename = secure_filename(file.filename)
        file_path = os.path.join(app.config['UPLOAD_FOLDER'], filename)
        file.save(file_path)
        
        # Load workbook
        if excel_processor.load_workbook(file_path):
            sheet_names = excel_processor.get_sheet_names()
            return jsonify({
                'success': True,
                'filename': filename,
                'sheets': sheet_names,
                'first_sheet': sheet_names[0] if sheet_names else None
            })
        else:
            return jsonify({'error': 'Failed to load Excel file'}), 400
    
    return jsonify({'error': 'Invalid file type'}), 400

@app.route('/api/sheets')
def get_sheets():
    """Get available sheets"""
    sheets = excel_processor.get_sheet_names()
    return jsonify({'sheets': sheets})

@app.route('/api/sheet/<sheet_name>')
def get_sheet_data(sheet_name):
    """Get sheet data with formatting"""
    start_row = request.args.get('start_row', 1, type=int)
    end_row = request.args.get('end_row', 50, type=int)
    start_col = request.args.get('start_col', 1, type=int)
    end_col = request.args.get('end_col', 26, type=int)
    
    data = excel_processor.get_sheet_data(sheet_name, start_row, end_row, start_col, end_col)
    if data:
        # Get column widths and row heights
        data['column_widths'] = excel_processor.get_column_widths(sheet_name)
        data['row_heights'] = excel_processor.get_row_heights(sheet_name)
        return jsonify(data)
    
    return jsonify({'error': 'Sheet not found'}), 404

@app.route('/convert-pdf', methods=['POST'])
def convert_to_pdf():
    """Convert Excel to PDF using pywin32"""
    data = request.get_json()
    sheet_name = data.get('sheet_name')
    cell_range = data.get('cell_range')
    page_size = data.get('page_size', 'A4')
    orientation = data.get('orientation', 'Portrait')
    
    if not excel_processor.filename:
        return jsonify({'error': 'No file loaded'}), 400
    
    try:
        # Get file path
        file_path = os.path.join(app.config['UPLOAD_FOLDER'], excel_processor.filename)
        
        # Create output filename
        base_name = os.path.splitext(excel_processor.filename)[0]
        output_file = os.path.join(app.config['UPLOAD_FOLDER'], f"{base_name}.pdf")
        
        # Convert using pywin32
        success = convert_excel_to_pdf(file_path, output_file, sheet_name, cell_range, page_size, orientation)
        
        if success:
            return jsonify({
                'success': True,
                'pdf_file': f"{base_name}.pdf",
                'message': 'PDF converted successfully'
            })
        else:
            return jsonify({'error': 'Failed to convert to PDF'}), 500
            
    except Exception as e:
        return jsonify({'error': f'Conversion error: {str(e)}'}), 500

def convert_excel_to_pdf(excel_file, output_pdf, sheet_name=None, cell_range=None, 
                        page_size="A4", orientation="Portrait"):
    """Convert Excel to PDF using pywin32"""
    try:
        excel = win32com.client.Dispatch("Excel.Application")
        excel.Visible = False
        
        workbook = excel.Workbooks.Open(excel_file)
        
        # Set active sheet if specified
        if sheet_name:
            try:
                worksheet = workbook.Sheets(sheet_name)
                worksheet.Activate()
            except:
                print(f"Sheet '{sheet_name}' not found")
                return False
        
        # Set page setup
        worksheet = workbook.ActiveSheet
        
        # Page size
        page_size_map = {
            "A4": 7,      # xlPaperA4
            "A3": 8,      # xlPaperA3
            "Letter": 1,  # xlPaperLetter
            "Legal": 5    # xlPaperLegal
        }
        
        if page_size in page_size_map:
            worksheet.PageSetup.PaperSize = page_size_map[page_size]
        
        # Orientation
        if orientation.lower() == "landscape":
            worksheet.PageSetup.Orientation = 2  # xlLandscape
        else:
            worksheet.PageSetup.Orientation = 1  # xlPortrait
        
        # Set print area if specified
        if cell_range:
            worksheet.PageSetup.PrintArea = cell_range
        
        # Convert to PDF
        workbook.ExportAsFixedFormat(0, output_pdf)
        
        workbook.Close(False)
        excel.Quit()
        
        return True
        
    except Exception as e:
        print(f"PDF conversion error: {e}")
        try:
            workbook.Close(False)
            excel.Quit()
        except:
            pass
        return False

@app.route('/download/<filename>')
def download_file(filename):
    """Download converted PDF file"""
    try:
        return send_file(
            os.path.join(app.config['UPLOAD_FOLDER'], filename),
            as_attachment=True,
            download_name=filename
        )
    except FileNotFoundError:
        return jsonify({'error': 'File not found'}), 404

if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0', port=5000)