#!/usr/bin/env python3
"""
Simple Excel to PDF Converter
Konversi file Excel ke PDF tanpa GUI
"""

import win32com.client
import os
import sys
import argparse

def excel_to_pdf(excel_file, output_pdf, sheet_name=None, cell_range=None, 
                page_size="A4", orientation="Portrait"):
    """
    Konversi file Excel ke PDF
    
    Args:
        excel_file (str): Path file Excel
        output_pdf (str): Path output PDF
        sheet_name (str): Nama sheet (opsional)
        cell_range (str): Range cell (opsional)
        page_size (str): Ukuran kertas (A4, A3, Letter, Legal)
        orientation (str): Orientasi (Portrait, Landscape)
    """
    
    # Validasi file input
    if not os.path.exists(excel_file):
        raise FileNotFoundError(f"File Excel tidak ditemukan: {excel_file}")
    
    # Inisialisasi Excel
    excel = win32com.client.Dispatch("Excel.Application")
    excel.Visible = False
    
    try:
        print(f"Membuka file Excel: {excel_file}")
        workbook = excel.Workbooks.Open(excel_file)
        
        # Pilih sheet jika ditentukan
        if sheet_name:
            try:
                worksheet = workbook.Sheets(sheet_name)
                print(f"Sheet aktif: {sheet_name}")
            except:
                print(f"Sheet '{sheet_name}' tidak ditemukan")
                print("Sheet yang tersedia:")
                for sheet in workbook.Sheets:
                    print(f"  - {sheet.Name}")
                return
        else:
            worksheet = workbook.ActiveSheet
            print(f"Sheet aktif: {worksheet.Name}")
        
        # Set pengaturan halaman
        print("Mengatur pengaturan halaman...")
        
        # Ukuran kertas
        page_size_map = {
            "A4": 7,      # xlPaperA4
            "A3": 8,      # xlPaperA3
            "Letter": 1,  # xlPaperLetter
            "Legal": 5    # xlPaperLegal
        }
        
        if page_size in page_size_map:
            worksheet.PageSetup.PaperSize = page_size_map[page_size]
            print(f"Ukuran kertas: {page_size}")
        
        # Orientasi
        if orientation.lower() == "landscape":
            worksheet.PageSetup.Orientation = 2  # xlLandscape
            print("Orientasi: Landscape")
        else:
            worksheet.PageSetup.Orientation = 1  # xlPortrait
            print("Orientasi: Portrait")
        
        # Set print area jika range ditentukan
        if cell_range:
            worksheet.PageSetup.PrintArea = cell_range
            print(f"Print area: {cell_range}")
        
        # Konversi ke PDF
        print(f"Mengkonversi ke PDF: {output_pdf}")
        workbook.ExportAsFixedFormat(0, output_pdf)
        
        print(f"✅ Konversi berhasil!")
        print(f"📄 PDF tersimpan di: {output_pdf}")
        
    except Exception as e:
        print(f"❌ Error saat konversi: {str(e)}")
        raise
    finally:
        # Tutup workbook dan Excel
        try:
            workbook.Close(False)
            excel.Quit()
        except:
            pass

def list_sheets(excel_file):
    """Menampilkan daftar sheet dalam file Excel"""
    
    if not os.path.exists(excel_file):
        print(f"❌ File Excel tidak ditemukan: {excel_file}")
        return
    
    excel = win32com.client.Dispatch("Excel.Application")
    excel.Visible = False
    
    try:
        workbook = excel.Workbooks.Open(excel_file)
        print(f"📊 Sheet dalam file '{os.path.basename(excel_file)}':")
        print("-" * 50)
        
        for i, sheet in enumerate(workbook.Sheets, 1):
            print(f"{i:2d}. {sheet.Name}")
            
    except Exception as e:
        print(f"❌ Error: {str(e)}")
    finally:
        try:
            workbook.Close(False)
            excel.Quit()
        except:
            pass

def main():
    parser = argparse.ArgumentParser(description="Excel to PDF Converter")
    parser.add_argument("excel_file", help="Path file Excel")
    parser.add_argument("-o", "--output", help="Path output PDF")
    parser.add_argument("-s", "--sheet", help="Nama sheet")
    parser.add_argument("-r", "--range", help="Range cell (contoh: A1:Z50)")
    parser.add_argument("-p", "--page-size", choices=["A4", "A3", "Letter", "Legal"], 
                       default="A4", help="Ukuran kertas")
    parser.add_argument("-t", "--orientation", choices=["Portrait", "Landscape"], 
                       default="Portrait", help="Orientasi")
    parser.add_argument("-l", "--list-sheets", action="store_true", 
                       help="Tampilkan daftar sheet")
    
    args = parser.parse_args()
    
    print("=" * 60)
    print("📊 Excel to PDF Converter")
    print("=" * 60)
    
    # List sheets jika diminta
    if args.list_sheets:
        list_sheets(args.excel_file)
        return
    
    # Tentukan output file
    if not args.output:
        base_name = os.path.splitext(args.excel_file)[0]
        args.output = f"{base_name}.pdf"
    
    # Konversi
    try:
        excel_to_pdf(
            excel_file=args.excel_file,
            output_pdf=args.output,
            sheet_name=args.sheet,
            cell_range=args.range,
            page_size=args.page_size,
            orientation=args.orientation
        )
    except Exception as e:
        print(f"❌ Konversi gagal: {str(e)}")
        sys.exit(1)

if __name__ == "__main__":
    main()