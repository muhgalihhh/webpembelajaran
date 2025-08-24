# 🎯 Excel Preview dengan Style Asli

Sistem ini memungkinkan Anda untuk menampilkan file Excel di web dengan **mempertahankan layout, style, dan format asli** dari file Excel. Tidak ada lagi kehilangan format, warna, font, atau alignment!

## ✨ Fitur Utama

### 🎨 **Preservasi Style Lengkap**
- ✅ **Font**: Nama, ukuran, bold, italic, underline, strikethrough, warna
- ✅ **Alignment**: Horizontal (left, center, right, justify), vertical (top, center, bottom), text wrapping
- ✅ **Border**: Style (thin, medium, thick, dashed, dotted, double), warna, ketebalan
- ✅ **Background**: Solid colors, pattern fills, gradient fills
- ✅ **Number Formatting**: Currency, date, percentage, custom formats
- ✅ **Layout**: Column widths, row heights, merged cells

### 🖥️ **Interface Modern**
- ✅ Upload file Excel (.xlsx, .xls) dengan drag & drop
- ✅ Pilihan sheet multiple dengan tab navigation
- ✅ Range selection opsional (A1:Z50)
- ✅ Preview real-time dengan style asli
- ✅ Download dan delete file
- ✅ Responsive design untuk semua device

### 🚀 **Performance & Security**
- ✅ File validation (type, size)
- ✅ CSRF protection
- ✅ Secure file storage
- ✅ Optimized for large files
- ✅ Caching untuk performance

## 🛠️ Instalasi & Setup

### 1. Clone Repository
```bash
git clone <repository-url>
cd <project-directory>
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Create Storage Directories
```bash
mkdir -p storage/app/public/excel-files
php artisan storage:link
```

### 5. Run Migrations (if needed)
```bash
php artisan migrate
```

### 6. Start Development Server
```bash
# Option 1: Use the provided script
./start_excel_preview.sh

# Option 2: Manual start
php artisan serve
```

## 📖 Cara Penggunaan

### 1. Akses Aplikasi
```
http://localhost:8000
```

### 2. Klik "Excel Preview"
Klik kartu "Excel Preview" di halaman utama atau akses langsung:
```
http://localhost:8000/excel-preview
```

### 3. Upload File Excel
- Klik "Choose File" untuk memilih file Excel (.xlsx atau .xls)
- Klik "Upload" untuk mengunggah file
- Sistem akan otomatis menampilkan preview

### 4. Kontrol Preview
- **Sheet Selection**: Pilih sheet yang ingin ditampilkan
- **Range Selection**: Masukkan range spesifik (opsional, contoh: A1:Z50)
- **Load Preview**: Klik untuk memuat ulang preview

### 5. Navigasi
- **Sheet Tabs**: Klik tab untuk beralih antar sheet
- **Scroll**: Gunakan scroll untuk melihat data yang lebih besar
- **Download**: Download file Excel asli
- **Delete**: Hapus file dari sistem

## 🧪 Testing

### 1. Create Sample Excel File
```bash
# Using Python (recommended)
python3 create_sample_excel.py

# Using PHP
php create_sample_excel.php
```

### 2. Test API Endpoints
```bash
python3 test_excel_preview_api.py
```

### 3. Manual Testing
1. Upload file `sample_excel_with_styles.xlsx`
2. Verify all styles are preserved
3. Test different sheets and ranges
4. Test download and delete functions

## 📁 Struktur File

```
├── app/
│   ├── Services/
│   │   └── ExcelPreviewService.php      # Service untuk membaca Excel dengan style
│   └── Http/Controllers/
│       └── ExcelPreviewController.php   # Controller untuk menangani request
├── resources/views/
│   └── excel-preview/
│       └── index.blade.php              # Interface utama
├── routes/
│   └── web.php                          # Routes untuk Excel preview
├── storage/
│   └── app/public/
│       └── excel-files/                 # Direktori penyimpanan file Excel
├── create_sample_excel.py               # Script untuk membuat file Excel contoh
├── test_excel_preview_api.py           # Script untuk testing API
├── start_excel_preview.sh              # Script untuk menjalankan server
└── EXCEL_PREVIEW_README.md             # Dokumentasi lengkap
```

## 🔌 API Endpoints

### Upload File
```http
POST /excel-preview/upload
Content-Type: multipart/form-data

Parameters:
- excel_file: File Excel (.xlsx, .xls)

Response:
{
    "success": true,
    "file_path": "excel-files/1234567890_file.xlsx",
    "file_name": "1234567890_file.xlsx",
    "sheet_names": ["Sheet1", "Sheet2"],
    "message": "File uploaded successfully"
}
```

### Load Preview
```http
POST /excel-preview/preview
Content-Type: application/json

Parameters:
{
    "file_path": "excel-files/1234567890_file.xlsx",
    "sheet_name": "Sheet1",
    "range": "A1:Z50"
}

Response:
{
    "success": true,
    "data": [...],           // Data Excel
    "styles": [...],         // Style information
    "css": {...},           // Generated CSS
    "merged_cells": [...],   // Merged cells info
    "column_widths": {...},  // Column widths
    "row_heights": {...},    // Row heights
    "sheet_name": "Sheet1",
    "range": "A1:Z50"
}
```

### Download File
```http
GET /excel-preview/download/{fileName}
```

### Delete File
```http
DELETE /excel-preview/delete/{fileName}
```

## 🎨 Style yang Didukung

### Font Properties
- Font family (Arial, Calibri, Times New Roman, dll)
- Font size (8pt, 10pt, 12pt, dll)
- Bold, italic, underline, strikethrough
- Font color (RGB, hex)

### Alignment
- Horizontal: left, center, right, justify
- Vertical: top, center, bottom
- Text wrapping
- Text rotation

### Borders
- Border style: thin, medium, thick, dashed, dotted, double
- Border color
- Individual sides: left, right, top, bottom

### Fill/Background
- Solid fill colors
- Pattern fills
- Gradient fills

### Number Formatting
- Currency format
- Date format
- Percentage format
- Custom number formats

## 📊 Contoh File Excel

File contoh `sample_excel_with_styles.xlsx` berisi:

### Sheet 1: Data Karyawan
- Header dengan background biru dan text putih
- Data dengan border dan alignment yang tepat
- Number formatting untuk gaji dan tanggal
- Color coding untuk status (hijau untuk aktif, merah untuk nonaktif)

### Sheet 2: Laporan Keuangan
- Header dengan background hijau
- Number formatting untuk currency dan percentage
- Right alignment untuk angka

### Sheet 3: Data Kompleks
- Merged cells untuk judul
- Header dengan background merah
- Data dengan berbagai format

## 🔧 Dependencies

- **PhpSpreadsheet**: Untuk membaca file Excel dengan style
- **Laravel**: Framework web
- **Alpine.js**: JavaScript framework untuk interactivity
- **Tailwind CSS**: CSS framework untuk styling
- **OpenPyXL**: Untuk membuat file Excel contoh (Python)

## 🚨 Troubleshooting

### File tidak dapat diupload
- Pastikan file berformat .xlsx atau .xls
- Ukuran file maksimal 10MB
- Pastikan direktori `storage/app/public/excel-files` memiliki permission write

### Style tidak tampil dengan benar
- Pastikan PhpSpreadsheet terinstall dengan benar
- Cek browser console untuk error JavaScript
- Pastikan CSS tidak terblokir oleh browser

### Performance issues
- Untuk file besar, gunakan range selection untuk membatasi data
- Pertimbangkan untuk menggunakan pagination untuk data yang sangat besar

### Server tidak start
- Pastikan PHP dan Composer terinstall
- Cek file permissions
- Pastikan port 8000 tidak digunakan aplikasi lain

## 🔒 Keamanan

- File Excel disimpan di direktori public dengan nama yang di-hash
- Validasi file type dan size
- CSRF protection untuk semua request
- Sanitasi input untuk range selection
- Secure file handling

## 🚀 Pengembangan

### Menambah Style Baru
Untuk menambah support style baru, edit `ExcelPreviewService.php`:

1. Tambahkan property baru di method `extractCellStyle()`
2. Update method `generateCssFromStyles()` untuk generate CSS yang sesuai

### Custom Styling
Anda dapat menambahkan custom CSS di `resources/views/excel-preview/index.blade.php` untuk mengubah tampilan interface.

### Extending Functionality
- Tambahkan support untuk format Excel lain (.xlsm, .xltx)
- Implementasi pagination untuk file besar
- Tambahkan export ke PDF dengan style
- Implementasi real-time collaboration

## 📝 License

Sistem ini dikembangkan untuk internal use. Pastikan untuk mematuhi lisensi dari dependencies yang digunakan.

## 🤝 Contributing

1. Fork repository
2. Create feature branch
3. Commit changes
4. Push to branch
5. Create Pull Request

## 📞 Support

Jika Anda mengalami masalah atau memiliki pertanyaan:

1. Cek dokumentasi di `EXCEL_PREVIEW_README.md`
2. Jalankan test suite dengan `test_excel_preview_api.py`
3. Cek log Laravel di `storage/logs/laravel.log`
4. Buat issue di repository

---

**🎉 Selamat menggunakan Excel Preview dengan Style Asli!**