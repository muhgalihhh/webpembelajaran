# Excel Preview dengan Style Asli

Sistem ini memungkinkan Anda untuk menampilkan file Excel di web dengan mempertahankan layout, style, dan format asli dari file Excel.

## Fitur Utama

✅ **Preservasi Style Lengkap**
- Font (nama, ukuran, bold, italic, underline, strikethrough, warna)
- Alignment (horizontal, vertical, text wrapping)
- Border (style, warna, ketebalan)
- Background color dan fill patterns
- Number formatting
- Column widths dan row heights

✅ **Interface Modern**
- Upload file Excel (.xlsx, .xls)
- Pilihan sheet multiple
- Range selection opsional
- Preview real-time dengan style asli
- Download dan delete file

✅ **Responsive Design**
- Tampilan yang responsif untuk berbagai ukuran layar
- Scroll horizontal dan vertical untuk data besar
- Loading indicators

## Cara Penggunaan

### 1. Akses Halaman Excel Preview
```
http://your-domain/excel-preview
```

### 2. Upload File Excel
- Klik "Choose File" untuk memilih file Excel (.xlsx atau .xls)
- Klik "Upload" untuk mengunggah file
- Sistem akan otomatis menampilkan preview

### 3. Kontrol Preview
- **Sheet Selection**: Pilih sheet yang ingin ditampilkan
- **Range Selection**: Masukkan range spesifik (opsional, contoh: A1:Z50)
- **Load Preview**: Klik untuk memuat ulang preview

### 4. Navigasi
- **Sheet Tabs**: Klik tab untuk beralih antar sheet
- **Scroll**: Gunakan scroll untuk melihat data yang lebih besar
- **Download**: Download file Excel asli
- **Delete**: Hapus file dari sistem

## Struktur File

```
app/
├── Services/
│   └── ExcelPreviewService.php      # Service untuk membaca Excel dengan style
├── Http/Controllers/
│   └── ExcelPreviewController.php   # Controller untuk menangani request
└── resources/views/
    └── excel-preview/
        └── index.blade.php          # Interface utama

routes/
└── web.php                          # Routes untuk Excel preview

storage/
└── app/public/
    └── excel-files/                 # Direktori penyimpanan file Excel
```

## API Endpoints

### Upload File
```
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
```
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
```
GET /excel-preview/download/{fileName}
```

### Delete File
```
DELETE /excel-preview/delete/{fileName}
```

## Style yang Didukung

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

## Contoh File Excel

Untuk testing, Anda dapat menggunakan file contoh yang dibuat dengan script `create_sample_excel.php`:

```bash
php create_sample_excel.php
```

File contoh akan berisi:
- Header dengan background biru dan text putih
- Data dengan border dan alignment yang tepat
- Number formatting untuk gaji dan tanggal
- Color coding untuk status (hijau untuk aktif, merah untuk nonaktif)
- Sheet kedua dengan data keuangan dan styling berbeda

## Dependencies

- **PhpSpreadsheet**: Untuk membaca file Excel dengan style
- **Laravel**: Framework web
- **Alpine.js**: JavaScript framework untuk interactivity
- **Tailwind CSS**: CSS framework untuk styling

## Troubleshooting

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

## Keamanan

- File Excel disimpan di direktori public dengan nama yang di-hash
- Validasi file type dan size
- CSRF protection untuk semua request
- Sanitasi input untuk range selection

## Pengembangan

### Menambah Style Baru
Untuk menambah support style baru, edit `ExcelPreviewService.php`:

1. Tambahkan property baru di method `extractCellStyle()`
2. Update method `generateCssFromStyles()` untuk generate CSS yang sesuai

### Custom Styling
Anda dapat menambahkan custom CSS di `resources/views/excel-preview/index.blade.php` untuk mengubah tampilan interface.

## License

Sistem ini dikembangkan untuk internal use. Pastikan untuk mematuhi lisensi dari dependencies yang digunakan.