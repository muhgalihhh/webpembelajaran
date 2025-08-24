# Panduan Penggunaan Excel Preview & PDF Converter

## Instalasi Cepat

### Windows
```bash
# Double click file run_app.bat
# Atau jalankan di Command Prompt:
run_app.bat
```

### Linux/Mac
```bash
# Jalankan script:
./run_app.sh
# Atau:
bash run_app.sh
```

## Instalasi Manual

1. **Install Python 3.7+**
2. **Install dependensi:**
   ```bash
   pip install -r requirements_simple.txt
   ```
3. **Install Microsoft Excel** (wajib)
4. **Buat file test (opsional):**
   ```bash
   pip install openpyxl
   python test_excel_converter.py
   ```

## Cara Menggunakan Aplikasi

### 1. Memulai Aplikasi
```bash
python excel_preview_converter_simple.py
```

### 2. Load File Excel
- Klik **"Browse"** untuk memilih file Excel (.xlsx atau .xls)
- Klik **"Load File"** untuk membuka file
- Status akan berubah menjadi "Loaded: [nama_file]"

### 3. Pilih Sheet
- Gunakan dropdown **"Sheet"** untuk memilih sheet yang aktif
- Aplikasi akan otomatis memilih sheet pertama

### 4. Atur Cell Range
- Masukkan range cell di field **"Cell Range"**
- Format: `A1:Z50` (default)
- Contoh: `A1:D10` untuk range kecil

### 5. Pengaturan Halaman
- **Page Size**: Pilih ukuran kertas (A4, A3, Letter, Legal)
- **Orientation**: Pilih orientasi (Portrait/Landscape)

### 6. Preview Data
- Klik **"Preview"** untuk melihat data Excel
- Data akan ditampilkan dalam format tabel
- Scroll untuk melihat data lebih banyak

### 7. Konversi ke PDF
- Klik **"Convert to PDF"**
- Pilih lokasi penyimpanan file PDF
- Tunggu proses konversi selesai

## Fitur Utama

### Preview Excel
- Menampilkan data dalam format tabel
- Scroll horizontal dan vertikal
- Truncate teks panjang untuk tampilan yang rapi
- Header kolom otomatis (A, B, C, dst)

### Pemilihan Sheet
- Dropdown untuk memilih sheet
- Otomatis load sheet pertama
- Switch antar sheet tanpa reload file

### Pengaturan Cell Range
- Format Excel standar (A1:Z50)
- Mendukung range custom
- Validasi range otomatis

### Pengaturan Halaman
- **Ukuran Kertas:**
  - A4 (210 x 297 mm)
  - A3 (297 x 420 mm)
  - Letter (216 x 279 mm)
  - Legal (216 x 356 mm)

- **Orientasi:**
  - Portrait (tegak)
  - Landscape (mendatar)

### Konversi PDF
- Kualitas tinggi
- Mempertahankan format Excel
- Pengaturan halaman otomatis
- Progress indicator

## Troubleshooting

### Error "Excel not found"
**Penyebab:** Microsoft Excel tidak terinstall
**Solusi:** Install Microsoft Excel

### Error "File in use"
**Penyebab:** File Excel sedang dibuka di aplikasi lain
**Solusi:** Tutup file Excel di aplikasi lain

### Error "Failed to load Excel file"
**Penyebab:** Path file salah atau file corrupt
**Solusi:** 
- Periksa path file
- Pastikan file Excel valid
- Coba file Excel lain

### Preview tidak muncul
**Penyebab:** Range cell tidak valid atau kosong
**Solusi:**
- Periksa range cell
- Pastikan ada data di range tersebut
- Coba range yang lebih kecil

### PDF tidak tersimpan
**Penyebab:** Permission atau disk space
**Solusi:**
- Periksa permission folder
- Pastikan ada ruang disk cukup
- Coba folder lain

### Aplikasi crash saat konversi
**Penyebab:** Memory atau Excel crash
**Solusi:**
- Restart aplikasi
- Tutup aplikasi lain
- Coba file Excel yang lebih kecil

## Tips Penggunaan

### Untuk File Besar
1. Gunakan range cell yang spesifik
2. Preview dulu sebelum konversi
3. Tutup aplikasi lain untuk menghemat memory

### Untuk Kualitas PDF Terbaik
1. Gunakan range cell yang tepat
2. Pilih ukuran kertas yang sesuai
3. Atur orientasi yang optimal

### Untuk Performa
1. Tutup file Excel yang tidak perlu
2. Restart aplikasi jika lambat
3. Gunakan range cell yang minimal

## Contoh Penggunaan

### Konversi Laporan Keuangan
1. Load file Excel laporan
2. Pilih sheet "Laporan Keuangan"
3. Set range: `A1:F20`
4. Page size: A4, Orientation: Portrait
5. Preview untuk memastikan
6. Convert to PDF

### Konversi Data Tabel
1. Load file Excel data
2. Pilih sheet yang berisi tabel
3. Set range sesuai ukuran tabel
4. Page size: A4, Orientation: Landscape
5. Preview dan convert

### Konversi Multiple Sheet
1. Load file Excel
2. Preview sheet pertama
3. Convert sheet pertama
4. Switch ke sheet kedua
5. Preview dan convert sheet kedua
6. Ulangi untuk sheet lainnya

## Struktur File

```
excel_converter/
├── excel_preview_converter_simple.py    # Aplikasi utama
├── test_excel_converter.py              # Script test
├── requirements_simple.txt              # Dependensi minimal
├── requirements_test.txt                # Dependensi untuk test
├── run_app.bat                         # Script Windows
├── run_app.sh                          # Script Linux
├── README.md                           # Dokumentasi utama
├── USAGE_GUIDE.md                      # Panduan ini
└── test_excel_converter.xlsx           # File test (generated)
```

## Support

Jika mengalami masalah:
1. Periksa troubleshooting di atas
2. Pastikan semua dependensi terinstall
3. Coba dengan file Excel yang berbeda
4. Restart aplikasi dan komputer jika perlu