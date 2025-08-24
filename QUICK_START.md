# Quick Start - Excel to PDF Converter

## 🚀 Instalasi Cepat

### Windows
```bash
# Double click file run_app.bat
run_app.bat
```

### Linux/Mac
```bash
# Jalankan script
./run_app.sh
```

## 📋 Prerequisites

- Python 3.7+
- Microsoft Excel
- pywin32

## ⚡ Penggunaan Cepat

### 1. GUI Version (Recommended)
```bash
python excel_preview_converter_simple.py
```

### 2. Command Line Version
```bash
# Konversi dasar
python simple_converter.py file.xlsx

# Dengan pengaturan
python simple_converter.py file.xlsx -s "Sheet1" -r "A1:Z50" -p A4
```

## 🎯 Fitur Utama

- ✅ Preview Excel data
- ✅ Pilih sheet aktif
- ✅ Set cell range
- ✅ Pengaturan halaman (A4, A3, Letter, Legal)
- ✅ Orientasi (Portrait/Landscape)
- ✅ Konversi ke PDF berkualitas tinggi

## 📁 File Utama

- `excel_preview_converter_simple.py` - GUI aplikasi
- `simple_converter.py` - Command line converter
- `test_excel_converter.py` - Buat file test Excel
- `run_app.bat` / `run_app.sh` - Script instalasi

## 🔧 Troubleshooting

| Error | Solusi |
|-------|--------|
| Excel not found | Install Microsoft Excel |
| File in use | Tutup file di aplikasi lain |
| Permission denied | Run as administrator |
| Memory error | Tutup aplikasi lain |

## 📖 Dokumentasi Lengkap

- [README.md](README.md) - Dokumentasi utama
- [USAGE_GUIDE.md](USAGE_GUIDE.md) - Panduan lengkap
- [examples.md](examples.md) - Contoh penggunaan

## 🆘 Support

Jika ada masalah:
1. Periksa troubleshooting di atas
2. Pastikan Excel terinstall
3. Coba restart aplikasi
4. Periksa file Excel tidak corrupt