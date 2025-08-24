# Contoh Penggunaan Excel to PDF Converter

## Command Line Converter

### 1. Konversi Dasar
```bash
# Konversi file Excel ke PDF dengan pengaturan default
python simple_converter.py file.xlsx

# Konversi dengan nama output custom
python simple_converter.py file.xlsx -o output.pdf
```

### 2. List Sheet
```bash
# Tampilkan daftar sheet dalam file Excel
python simple_converter.py file.xlsx -l
```

### 3. Pilih Sheet Tertentu
```bash
# Konversi sheet "Laporan" ke PDF
python simple_converter.py file.xlsx -s "Laporan"

# Konversi sheet "Data" dengan output custom
python simple_converter.py file.xlsx -s "Data" -o data_report.pdf
```

### 4. Set Range Cell
```bash
# Konversi range A1:D20
python simple_converter.py file.xlsx -r "A1:D20"

# Konversi sheet tertentu dengan range
python simple_converter.py file.xlsx -s "Laporan" -r "A1:F50"
```

### 5. Pengaturan Halaman
```bash
# Konversi dengan ukuran A3
python simple_converter.py file.xlsx -p A3

# Konversi dengan orientasi landscape
python simple_converter.py file.xlsx -t Landscape

# Kombinasi pengaturan
python simple_converter.py file.xlsx -p Letter -t Landscape -r "A1:Z100"
```

### 6. Contoh Lengkap
```bash
# Konversi laporan keuangan
python simple_converter.py laporan_keuangan.xlsx \
    -s "Laporan Bulanan" \
    -r "A1:G30" \
    -p A4 \
    -t Portrait \
    -o laporan_keuangan.pdf
```

## GUI Converter

### 1. Jalankan Aplikasi
```bash
# Versi sederhana (tanpa PIL/pandas)
python excel_preview_converter_simple.py

# Versi lengkap (dengan PIL/pandas)
python excel_preview_converter.py
```

### 2. Langkah-langkah GUI
1. **Browse** → Pilih file Excel
2. **Load File** → Buka file
3. **Sheet** → Pilih sheet (opsional)
4. **Cell Range** → Masukkan range (contoh: A1:Z50)
5. **Page Size** → Pilih ukuran kertas
6. **Orientation** → Pilih orientasi
7. **Preview** → Lihat preview data
8. **Convert to PDF** → Konversi ke PDF

## Contoh File Excel

### Struktur Data Sample
```
Sheet: Data Sample
A1:E6
┌─────────────┬─────┬──────────┬────────────┬─────────┐
│ Nama        │ Usia│ Kota     │ Pekerjaan  │ Gaji    │
├─────────────┼─────┼──────────┼────────────┼─────────┤
│ John Doe    │ 30  │ Jakarta  │ Programmer │ 5000000 │
│ Jane Smith  │ 25  │ Bandung  │ Designer   │ 4000000 │
│ Bob Johnson │ 35  │ Surabaya │ Manager    │ 8000000 │
│ Alice Brown │ 28  │ Medan    │ Analyst    │ 6000000 │
│ Charlie     │ 32  │ Semarang │ Developer  │ 5500000 │
└─────────────┴─────┴──────────┴────────────┴─────────┘

Sheet: Laporan
A1:B7
┌─────────────────┬─────────────┐
│ LAPORAN KEUANGAN│             │
├─────────────────┼─────────────┤
│ Periode: Jan 24 │             │
├─────────────────┼─────────────┤
│ Pendapatan      │ 15,000,000  │
│ Pengeluaran     │ 8,000,000   │
│ Laba            │ 7,000,000   │
└─────────────────┴─────────────┘
```

## Batch Processing

### 1. Konversi Multiple File
```bash
# Script batch untuk konversi multiple file
for file in *.xlsx; do
    python simple_converter.py "$file" -o "${file%.xlsx}.pdf"
done
```

### 2. Konversi dengan Pengaturan Sama
```bash
# Konversi semua file dengan pengaturan A4 Landscape
for file in *.xlsx; do
    python simple_converter.py "$file" \
        -p A4 \
        -t Landscape \
        -r "A1:Z50" \
        -o "pdf/${file%.xlsx}.pdf"
done
```

## Troubleshooting Examples

### 1. File Tidak Ditemukan
```bash
# Error: File Excel tidak ditemukan
python simple_converter.py nonexistent.xlsx
# Solusi: Periksa path file
```

### 2. Sheet Tidak Ditemukan
```bash
# Error: Sheet 'Laporan' tidak ditemukan
python simple_converter.py file.xlsx -s "Laporan"
# Solusi: List sheet dulu
python simple_converter.py file.xlsx -l
```

### 3. Range Invalid
```bash
# Error: Range cell tidak valid
python simple_converter.py file.xlsx -r "A1:INVALID"
# Solusi: Gunakan format Excel standar
python simple_converter.py file.xlsx -r "A1:Z50"
```

## Performance Tips

### 1. File Besar
```bash
# Gunakan range yang spesifik
python simple_converter.py large_file.xlsx -r "A1:D100"

# Tutup aplikasi lain untuk menghemat memory
```

### 2. Multiple Konversi
```bash
# Konversi satu per satu untuk menghindari memory leak
for file in *.xlsx; do
    python simple_converter.py "$file"
    echo "Selesai: $file"
done
```

### 3. Monitoring
```bash
# Tambahkan progress indicator
for file in *.xlsx; do
    echo "Converting: $file"
    python simple_converter.py "$file"
    echo "Done: $file"
done
```

## Integration Examples

### 1. Dengan Python Script
```python
import subprocess
import os

def convert_excel_files(folder_path):
    for file in os.listdir(folder_path):
        if file.endswith('.xlsx'):
            input_file = os.path.join(folder_path, file)
            output_file = input_file.replace('.xlsx', '.pdf')
            
            cmd = [
                'python', 'simple_converter.py',
                input_file,
                '-o', output_file,
                '-p', 'A4',
                '-t', 'Portrait'
            ]
            
            subprocess.run(cmd)
            print(f"Converted: {file}")

# Usage
convert_excel_files('./excel_files/')
```

### 2. Dengan Cron Job (Linux)
```bash
# Edit crontab
crontab -e

# Tambahkan job untuk konversi otomatis setiap jam
0 * * * * cd /path/to/converter && python simple_converter.py /path/to/excel/file.xlsx
```

### 3. Dengan Windows Task Scheduler
```batch
@echo off
cd /d "C:\path\to\converter"
python simple_converter.py "C:\path\to\excel\file.xlsx"
```

## Advanced Usage

### 1. Custom Page Setup
```python
# Dalam script Python
import win32com.client

excel = win32com.client.Dispatch("Excel.Application")
workbook = excel.Workbooks.Open("file.xlsx")
worksheet = workbook.ActiveSheet

# Custom margins
worksheet.PageSetup.TopMargin = 50
worksheet.PageSetup.BottomMargin = 50
worksheet.PageSetup.LeftMargin = 50
worksheet.PageSetup.RightMargin = 50

# Custom header/footer
worksheet.PageSetup.CenterHeader = "Laporan Keuangan"
worksheet.PageSetup.CenterFooter = "Halaman &P dari &N"

workbook.ExportAsFixedFormat(0, "output.pdf")
```

### 2. Multiple Sheet Processing
```python
import win32com.client

def convert_all_sheets(excel_file, output_prefix):
    excel = win32com.client.Dispatch("Excel.Application")
    workbook = excel.Workbooks.Open(excel_file)
    
    for sheet in workbook.Sheets:
        sheet_name = sheet.Name
        output_file = f"{output_prefix}_{sheet_name}.pdf"
        
        # Set active sheet
        sheet.Activate()
        
        # Convert to PDF
        workbook.ExportAsFixedFormat(0, output_file)
        print(f"Converted sheet: {sheet_name}")
    
    workbook.Close(False)
    excel.Quit()

# Usage
convert_all_sheets("file.xlsx", "output")
```