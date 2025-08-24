# Excel Preview & PDF Converter

Aplikasi GUI untuk preview file Excel dan konversi ke PDF menggunakan pywin32.

## Fitur

- **Preview Excel**: Melihat isi file Excel dalam format tabel
- **Pemilihan Sheet**: Beralih antar sheet dalam workbook
- **Pemilihan Cell Range**: Menentukan range cell yang akan dikonversi
- **Pengaturan Halaman**: Mengatur ukuran kertas (A4, A3, Letter, Legal) dan orientasi (Portrait/Landscape)
- **Konversi ke PDF**: Mengkonversi Excel ke PDF dengan pengaturan yang telah ditentukan

## Instalasi

1. Pastikan Python 3.7+ sudah terinstall
2. Install dependensi:
   ```bash
   pip install -r requirements.txt
   ```
3. Pastikan Microsoft Excel terinstall di sistem

## Penggunaan

1. Jalankan aplikasi:
   ```bash
   python excel_preview_converter.py
   ```

2. **Load File Excel**:
   - Klik "Browse" untuk memilih file Excel (.xlsx atau .xls)
   - Klik "Load File" untuk membuka file

3. **Pilih Sheet**:
   - Gunakan dropdown "Sheet" untuk memilih sheet yang aktif

4. **Atur Cell Range**:
   - Masukkan range cell (contoh: A1:Z50) di field "Cell Range"

5. **Pengaturan Halaman**:
   - Pilih ukuran kertas: A4, A3, Letter, atau Legal
   - Pilih orientasi: Portrait atau Landscape

6. **Preview**:
   - Klik "Preview" untuk melihat tampilan data Excel

7. **Konversi ke PDF**:
   - Klik "Convert to PDF" untuk mengkonversi
   - Pilih lokasi penyimpanan file PDF

## Struktur Kode

- `ExcelPreviewConverter`: Class utama aplikasi
- `create_widgets()`: Membuat interface GUI
- `load_excel_file()`: Membuka file Excel menggunakan pywin32
- `preview_excel()`: Menampilkan preview data Excel
- `convert_to_pdf()`: Mengkonversi Excel ke PDF
- `display_preview()`: Menampilkan data dalam canvas

## Catatan

- Aplikasi memerlukan Microsoft Excel terinstall
- File Excel akan dibuka dalam background (tidak terlihat)
- Pastikan file Excel tidak sedang dibuka di aplikasi lain
- Aplikasi akan otomatis menutup Excel saat aplikasi ditutup

## Troubleshooting

1. **Error "Excel not found"**: Pastikan Microsoft Excel terinstall
2. **Error "File in use"**: Tutup file Excel di aplikasi lain
3. **Preview tidak muncul**: Pastikan range cell valid dan data ada
4. **PDF tidak tersimpan**: Pastikan folder tujuan memiliki permission write
