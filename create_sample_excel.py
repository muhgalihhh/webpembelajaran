#!/usr/bin/env python3
"""
Script untuk membuat file Excel contoh dengan berbagai style dan format
"""

import openpyxl
from openpyxl.styles import Font, Alignment, Border, Side, PatternFill, Color
from openpyxl.utils import get_column_letter
from datetime import datetime

def create_sample_excel():
    # Buat workbook baru
    wb = openpyxl.Workbook()
    
    # Sheet pertama - Data Karyawan
    ws1 = wb.active
    ws1.title = "Data Utama"
    
    # Data untuk sheet pertama
    data1 = [
        ['Nama', 'Usia', 'Kota', 'Gaji', 'Tanggal Bergabung', 'Status'],
        ['John Doe', 30, 'Jakarta', 5000000, '2023-01-15', 'Aktif'],
        ['Jane Smith', 25, 'Bandung', 4500000, '2023-03-20', 'Aktif'],
        ['Bob Johnson', 35, 'Surabaya', 6000000, '2022-11-10', 'Aktif'],
        ['Alice Brown', 28, 'Medan', 4800000, '2023-02-28', 'Nonaktif'],
        ['Charlie Wilson', 32, 'Semarang', 5200000, '2023-04-05', 'Aktif'],
    ]
    
    # Masukkan data
    for row_idx, row_data in enumerate(data1, 1):
        for col_idx, value in enumerate(row_data, 1):
            cell = ws1.cell(row=row_idx, column=col_idx, value=value)
    
    # Style untuk header
    header_font = Font(name='Arial', size=12, bold=True, color='FFFFFF')
    header_fill = PatternFill(start_color='4472C4', end_color='4472C4', fill_type='solid')
    header_alignment = Alignment(horizontal='center', vertical='center')
    header_border = Border(
        left=Side(style='thin', color='000000'),
        right=Side(style='thin', color='000000'),
        top=Side(style='thin', color='000000'),
        bottom=Side(style='thin', color='000000')
    )
    
    # Terapkan style ke header
    for col in range(1, 7):
        cell = ws1.cell(row=1, column=col)
        cell.font = header_font
        cell.fill = header_fill
        cell.alignment = header_alignment
        cell.border = header_border
    
    # Style untuk data
    data_border = Border(
        left=Side(style='thin', color='CCCCCC'),
        right=Side(style='thin', color='CCCCCC'),
        top=Side(style='thin', color='CCCCCC'),
        bottom=Side(style='thin', color='CCCCCC')
    )
    data_alignment = Alignment(vertical='center')
    
    # Terapkan style ke data
    for row in range(2, 7):
        for col in range(1, 7):
            cell = ws1.cell(row=row, column=col)
            cell.border = data_border
            cell.alignment = data_alignment
    
    # Style khusus untuk kolom tertentu
    # Usia - center aligned
    for row in range(2, 7):
        cell = ws1.cell(row=row, column=2)
        cell.alignment = Alignment(horizontal='center', vertical='center')
    
    # Gaji - right aligned dengan format number
    for row in range(2, 7):
        cell = ws1.cell(row=row, column=4)
        cell.alignment = Alignment(horizontal='right', vertical='center')
        cell.number_format = '#,##0'
    
    # Tanggal - format date
    for row in range(2, 7):
        cell = ws1.cell(row=row, column=5)
        cell.number_format = 'dd/mm/yyyy'
    
    # Status - center aligned dengan warna
    for row in range(2, 7):
        cell = ws1.cell(row=row, column=6)
        cell.alignment = Alignment(horizontal='center', vertical='center')
        
        # Warna berdasarkan status
        if cell.value == 'Aktif':
            cell.fill = PatternFill(start_color='90EE90', end_color='90EE90', fill_type='solid')
        else:
            cell.fill = PatternFill(start_color='FFB6C1', end_color='FFB6C1', fill_type='solid')
    
    # Set column widths
    ws1.column_dimensions['A'].width = 15
    ws1.column_dimensions['B'].width = 10
    ws1.column_dimensions['C'].width = 15
    ws1.column_dimensions['D'].width = 15
    ws1.column_dimensions['E'].width = 18
    ws1.column_dimensions['F'].width = 12
    
    # Set row heights
    ws1.row_dimensions[1].height = 25
    for row in range(2, 7):
        ws1.row_dimensions[row].height = 20
    
    # Sheet kedua - Laporan Keuangan
    ws2 = wb.create_sheet("Laporan Keuangan")
    
    # Data untuk sheet kedua
    data2 = [
        ['Bulan', 'Pendapatan', 'Pengeluaran', 'Profit', 'Margin %'],
        ['Januari', 15000000, 12000000, 3000000, 20],
        ['Februari', 18000000, 14000000, 4000000, 22.2],
        ['Maret', 22000000, 16000000, 6000000, 27.3],
        ['April', 25000000, 18000000, 7000000, 28],
        ['Mei', 28000000, 20000000, 8000000, 28.6],
    ]
    
    # Masukkan data
    for row_idx, row_data in enumerate(data2, 1):
        for col_idx, value in enumerate(row_data, 1):
            cell = ws2.cell(row=row_idx, column=col_idx, value=value)
    
    # Style untuk header keuangan
    fin_header_font = Font(name='Arial', size=11, bold=True, color='FFFFFF')
    fin_header_fill = PatternFill(start_color='70AD47', end_color='70AD47', fill_type='solid')
    fin_header_alignment = Alignment(horizontal='center', vertical='center')
    fin_header_border = Border(
        left=Side(style='medium', color='000000'),
        right=Side(style='medium', color='000000'),
        top=Side(style='medium', color='000000'),
        bottom=Side(style='medium', color='000000')
    )
    
    # Terapkan style ke header keuangan
    for col in range(1, 6):
        cell = ws2.cell(row=1, column=col)
        cell.font = fin_header_font
        cell.fill = fin_header_fill
        cell.alignment = fin_header_alignment
        cell.border = fin_header_border
    
    # Style untuk data keuangan
    fin_data_border = Border(
        left=Side(style='thin', color='000000'),
        right=Side(style='thin', color='000000'),
        top=Side(style='thin', color='000000'),
        bottom=Side(style='thin', color='000000')
    )
    
    # Terapkan style ke data keuangan
    for row in range(2, 7):
        for col in range(1, 6):
            cell = ws2.cell(row=row, column=col)
            cell.border = fin_data_border
    
    # Format number untuk kolom keuangan
    for row in range(2, 7):
        # Pendapatan, Pengeluaran, Profit
        for col in [2, 3, 4]:
            cell = ws2.cell(row=row, column=col)
            cell.number_format = '#,##0'
            cell.alignment = Alignment(horizontal='right', vertical='center')
        
        # Margin %
        cell = ws2.cell(row=row, column=5)
        cell.number_format = '0.0'
        cell.alignment = Alignment(horizontal='right', vertical='center')
    
    # Set column widths untuk sheet keuangan
    ws2.column_dimensions['A'].width = 12
    ws2.column_dimensions['B'].width = 15
    ws2.column_dimensions['C'].width = 15
    ws2.column_dimensions['D'].width = 15
    ws2.column_dimensions['E'].width = 12
    
    # Set row heights untuk sheet keuangan
    ws2.row_dimensions[1].height = 25
    for row in range(2, 7):
        ws2.row_dimensions[row].height = 20
    
    # Sheet ketiga - Data dengan merged cells
    ws3 = wb.create_sheet("Data Kompleks")
    
    # Data dengan merged cells
    ws3['A1'] = 'LAPORAN PENJUALAN TRIWULAN 2024'
    ws3.merge_cells('A1:E1')
    
    ws3['A3'] = 'Wilayah'
    ws3['B3'] = 'Q1'
    ws3['C3'] = 'Q2'
    ws3['D3'] = 'Q3'
    ws3['E3'] = 'Total'
    
    ws3['A4'] = 'Jawa Barat'
    ws3['B4'] = 15000000
    ws3['C4'] = 18000000
    ws3['D4'] = 22000000
    ws3['E4'] = 55000000
    
    ws3['A5'] = 'Jawa Tengah'
    ws3['B5'] = 12000000
    ws3['C5'] = 15000000
    ws3['D5'] = 18000000
    ws3['E5'] = 45000000
    
    ws3['A6'] = 'Jawa Timur'
    ws3['B6'] = 20000000
    ws3['C6'] = 25000000
    ws3['D6'] = 30000000
    ws3['E6'] = 75000000
    
    # Style untuk judul
    title_font = Font(name='Arial', size=16, bold=True, color='FFFFFF')
    title_fill = PatternFill(start_color='FF6B6B', end_color='FF6B6B', fill_type='solid')
    title_alignment = Alignment(horizontal='center', vertical='center')
    
    title_cell = ws3['A1']
    title_cell.font = title_font
    title_cell.fill = title_fill
    title_cell.alignment = title_alignment
    
    # Style untuk header
    header_font = Font(name='Arial', size=12, bold=True, color='FFFFFF')
    header_fill = PatternFill(start_color='4ECDC4', end_color='4ECDC4', fill_type='solid')
    header_alignment = Alignment(horizontal='center', vertical='center')
    header_border = Border(
        left=Side(style='thin', color='000000'),
        right=Side(style='thin', color='000000'),
        top=Side(style='thin', color='000000'),
        bottom=Side(style='thin', color='000000')
    )
    
    # Terapkan style ke header
    for col in range(1, 6):
        cell = ws3.cell(row=3, column=col)
        cell.font = header_font
        cell.fill = header_fill
        cell.alignment = header_alignment
        cell.border = header_border
    
    # Style untuk data
    data_border = Border(
        left=Side(style='thin', color='CCCCCC'),
        right=Side(style='thin', color='CCCCCC'),
        top=Side(style='thin', color='CCCCCC'),
        bottom=Side(style='thin', color='CCCCCC')
    )
    
    # Terapkan style ke data
    for row in range(4, 7):
        for col in range(1, 6):
            cell = ws3.cell(row=row, column=col)
            cell.border = data_border
            
            # Format number untuk kolom angka
            if col > 1:
                cell.number_format = '#,##0'
                cell.alignment = Alignment(horizontal='right', vertical='center')
            else:
                cell.alignment = Alignment(vertical='center')
    
    # Set column widths
    ws3.column_dimensions['A'].width = 15
    ws3.column_dimensions['B'].width = 15
    ws3.column_dimensions['C'].width = 15
    ws3.column_dimensions['D'].width = 15
    ws3.column_dimensions['E'].width = 15
    
    # Set row heights
    ws3.row_dimensions[1].height = 30
    ws3.row_dimensions[3].height = 25
    for row in range(4, 7):
        ws3.row_dimensions[row].height = 20
    
    # Simpan file
    filename = 'sample_excel_with_styles.xlsx'
    wb.save(filename)
    
    print(f"✅ File Excel contoh berhasil dibuat: {filename}")
    print("\n📋 File berisi:")
    print("- Sheet 1: Data Karyawan dengan header biru dan status berwarna")
    print("- Sheet 2: Laporan Keuangan dengan header hijau")
    print("- Sheet 3: Data Kompleks dengan merged cells dan header merah")
    print("\n🎨 Style yang diterapkan:")
    print("- Font: Arial dengan berbagai ukuran dan warna")
    print("- Background: Biru, hijau, merah, dan abu-abu")
    print("- Border: Thin, medium dengan warna hitam dan abu-abu")
    print("- Alignment: Center, right, dan vertical center")
    print("- Number format: Currency dan percentage")
    print("- Merged cells untuk judul")
    print("- Column widths dan row heights yang disesuaikan")

if __name__ == "__main__":
    try:
        create_sample_excel()
    except ImportError:
        print("❌ Error: openpyxl tidak terinstall")
        print("Install dengan: pip install openpyxl")
    except Exception as e:
        print(f"❌ Error: {e}")