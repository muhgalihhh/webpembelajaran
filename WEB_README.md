# Excel Preview & PDF Converter - Web Version

## 🌐 Overview

Aplikasi web untuk preview file Excel dengan format asli dan konversi ke PDF. Aplikasi ini menggunakan Flask sebagai backend dan menampilkan Excel dengan mempertahankan semua formatting asli seperti font, warna, border, alignment, dan number format.

## ✨ Fitur Utama

### 🎨 **Preserved Excel Formatting**
- ✅ **Font Styles**: Bold, italic, underline, font size, font color
- ✅ **Cell Alignment**: Horizontal (left, center, right), vertical (top, middle, bottom)
- ✅ **Borders**: Thin, medium, thick, dashed, dotted, double
- ✅ **Background Colors**: Fill colors dengan RGB values
- ✅ **Number Formatting**: Currency, date, number formats
- ✅ **Cell Merging**: Support untuk merged cells
- ✅ **Column Widths & Row Heights**: Dimensi asli Excel

### 📊 **Interactive Preview**
- 🔍 **Zoom Controls**: Zoom in/out dan reset zoom
- 📱 **Responsive Design**: Works di desktop dan mobile
- 🖱️ **Hover Effects**: Cell highlighting dan tooltips
- 📜 **Scrollable**: Horizontal dan vertical scrolling
- 🎯 **Range Selection**: Pilih range cell custom

### 🔧 **PDF Conversion**
- 📄 **Page Settings**: A4, A3, Letter, Legal
- 📐 **Orientation**: Portrait dan Landscape
- 🎯 **Print Area**: Set range cell untuk konversi
- 💾 **Download**: Download PDF hasil konversi

## 🚀 Quick Start

### 1. Install Dependencies
```bash
pip install -r requirements_web.txt
```

### 2. Run Application
```bash
python run_web_app.py
```

### 3. Open Browser
Aplikasi akan otomatis terbuka di browser di `http://localhost:5000`

## 📁 File Structure

```
web_excel_preview/
├── web_excel_preview.py          # Flask application
├── run_web_app.py               # Launcher script
├── requirements_web.txt         # Web dependencies
├── templates/
│   └── index.html              # Main HTML template
├── static/
│   ├── css/
│   │   └── style.css           # Excel styling CSS
│   └── js/
│       └── app.js              # Frontend JavaScript
├── uploads/                    # Upload folder (auto-created)
└── WEB_README.md              # This documentation
```

## 🎯 Cara Penggunaan

### 1. Upload File Excel
- Klik "Choose File" untuk memilih file Excel (.xlsx atau .xls)
- Klik "Upload & Load" untuk memuat file
- File akan diupload ke server dan sheet akan dimuat

### 2. Pilih Sheet
- Gunakan dropdown "Pilih Sheet" untuk memilih sheet aktif
- Sheet akan otomatis dimuat dengan format asli

### 3. Set Cell Range (Opsional)
- Masukkan range cell (contoh: A1:Z50)
- Klik "Load Range" untuk memuat range tertentu
- Default: A1:Z50

### 4. Preview Excel
- Excel akan ditampilkan dengan format asli
- Gunakan zoom controls untuk memperbesar/memperkecil
- Scroll untuk melihat data lebih banyak

### 5. Convert to PDF
- Pilih ukuran kertas (A4, A3, Letter, Legal)
- Pilih orientasi (Portrait/Landscape)
- Klik "Convert to PDF"
- Download PDF hasil konversi

## 🎨 Formatting Support

### Font Formatting
```css
.cell-bold { font-weight: bold; }
.cell-italic { font-style: italic; }
.cell-underline { text-decoration: underline; }
```

### Alignment
```css
.cell-center { text-align: center; }
.cell-right { text-align: right; }
.cell-left { text-align: left; }
.cell-top { vertical-align: top; }
.cell-bottom { vertical-align: bottom; }
```

### Borders
```css
.border-thin { border-width: 1px; }
.border-medium { border-width: 2px; }
.border-thick { border-width: 3px; }
.border-dashed { border-style: dashed; }
.border-dotted { border-style: dotted; }
.border-double { border-style: double; }
```

### Number Formats
```css
.currency-format { color: #388e3c; }
.date-format { color: #1976d2; }
.number-format { font-family: monospace; }
```

## 🔧 Technical Details

### Backend (Flask)
- **Framework**: Flask 2.3.3
- **Excel Processing**: openpyxl 3.1.2
- **PDF Conversion**: pywin32 (Excel COM)
- **File Upload**: Werkzeug

### Frontend
- **CSS Framework**: Bootstrap 5.1.3
- **Icons**: Font Awesome 6.0.0
- **JavaScript**: Vanilla JS (ES6+)
- **Responsive**: Mobile-first design

### Excel Processing
```python
# Load workbook with formatting
workbook = openpyxl.load_workbook(file_path, data_only=False)

# Extract cell formatting
cell_info = {
    'value': cell.value,
    'styles': {
        'font': {...},
        'alignment': {...},
        'border': {...},
        'fill': {...},
        'number_format': cell.number_format
    }
}
```

### PDF Conversion
```python
# Use pywin32 for high-quality conversion
excel = win32com.client.Dispatch("Excel.Application")
workbook = excel.Workbooks.Open(excel_file)
workbook.ExportAsFixedFormat(0, output_pdf)
```

## 🌐 API Endpoints

### Upload File
```
POST /upload
Content-Type: multipart/form-data
```

### Get Sheets
```
GET /api/sheets
Response: {"sheets": ["Sheet1", "Sheet2"]}
```

### Get Sheet Data
```
GET /api/sheet/{sheet_name}?start_row=1&end_row=50&start_col=1&end_col=26
Response: {"data": [...], "max_row": 100, "max_col": 26}
```

### Convert to PDF
```
POST /convert-pdf
Content-Type: application/json
Body: {
    "sheet_name": "Sheet1",
    "cell_range": "A1:Z50",
    "page_size": "A4",
    "orientation": "Portrait"
}
```

### Download PDF
```
GET /download/{filename}
```

## 🔍 Troubleshooting

### Common Issues

#### 1. Port 5000 Already in Use
```bash
# Kill process using port 5000
lsof -ti:5000 | xargs kill -9

# Or use different port
python web_excel_preview.py --port 5001
```

#### 2. Excel Not Found (PDF Conversion)
- Pastikan Microsoft Excel terinstall
- Pastikan pywin32 terinstall dengan benar
- Restart aplikasi jika perlu

#### 3. File Upload Failed
- Periksa ukuran file (max 16MB)
- Pastikan format file (.xlsx atau .xls)
- Periksa permission folder uploads

#### 4. Formatting Not Preserved
- Pastikan file Excel memiliki formatting
- Coba file Excel yang berbeda
- Periksa console browser untuk error

### Performance Tips

#### 1. Large Files
- Gunakan range cell yang spesifik
- Tutup browser tab lain
- Restart aplikasi jika lambat

#### 2. Memory Usage
- Aplikasi menyimpan file di memory
- Tutup file yang tidak perlu
- Restart server jika memory penuh

#### 3. Network
- File diupload ke server
- Gunakan koneksi stabil
- Periksa firewall settings

## 🔮 Future Enhancements

### Planned Features
1. **Real-time Collaboration**: Multiple users
2. **Advanced Filtering**: Filter dan sort data
3. **Chart Support**: Tampilkan charts Excel
4. **Formula Evaluation**: Calculate formulas
5. **Cell Editing**: Edit cell values
6. **Export Options**: CSV, JSON, XML
7. **Authentication**: User login system
8. **File Management**: Save/load files

### Technical Improvements
1. **WebSocket**: Real-time updates
2. **Caching**: Redis untuk performance
3. **Async Processing**: Background tasks
4. **API Documentation**: Swagger/OpenAPI
5. **Testing**: Unit dan integration tests
6. **Docker**: Container deployment
7. **CI/CD**: Automated deployment

## 📊 Performance Metrics

### File Size Limits
- **Upload**: 16MB maximum
- **Preview**: 1000 rows × 100 columns recommended
- **PDF**: No size limit (depends on Excel)

### Response Times
- **File Upload**: 1-5 seconds (depending on size)
- **Sheet Load**: 0.5-2 seconds
- **PDF Conversion**: 2-10 seconds
- **Range Load**: 0.2-1 second

### Browser Support
- ✅ Chrome 90+
- ✅ Firefox 88+
- ✅ Safari 14+
- ✅ Edge 90+
- ⚠️ IE 11 (limited support)

## 🛡️ Security Considerations

### File Upload Security
- File type validation (.xlsx, .xls only)
- File size limits (16MB)
- Secure filename handling
- Temporary file storage

### Server Security
- Input validation
- Error handling
- CORS configuration
- Rate limiting (future)

### Data Privacy
- Files stored temporarily
- No data persistence
- Automatic cleanup
- No logging of file contents

## 📞 Support

### Getting Help
1. Periksa troubleshooting section
2. Periksa console browser (F12)
3. Periksa server logs
4. Coba file Excel yang berbeda

### Reporting Issues
- Deskripsikan masalah dengan detail
- Sertakan file Excel contoh (jika mungkin)
- Sertakan error messages
- Sertakan browser dan OS info

### Contributing
- Fork repository
- Create feature branch
- Submit pull request
- Follow coding standards

## 📄 License

This project is open source and available under the MIT License.

---

**Happy Excel Previewing! 🎉**