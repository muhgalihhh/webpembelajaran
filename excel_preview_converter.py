import tkinter as tk
from tkinter import ttk, filedialog, messagebox
import win32com.client
import os
import pandas as pd
from PIL import Image, ImageTk
import threading
import tempfile

class ExcelPreviewConverter:
    def __init__(self, root):
        self.root = root
        self.root.title("Excel Preview & PDF Converter")
        self.root.geometry("1200x800")
        
        # Variables
        self.excel_file = None
        self.excel_app = None
        self.workbook = None
        self.current_sheet = None
        self.selected_cells = []
        self.preview_image = None
        
        # Create GUI
        self.create_widgets()
        
    def create_widgets(self):
        # Main frame
        main_frame = ttk.Frame(self.root, padding="10")
        main_frame.grid(row=0, column=0, sticky=(tk.W, tk.E, tk.N, tk.S))
        
        # Configure grid weights
        self.root.columnconfigure(0, weight=1)
        self.root.rowconfigure(0, weight=1)
        main_frame.columnconfigure(1, weight=1)
        main_frame.rowconfigure(2, weight=1)
        
        # File selection frame
        file_frame = ttk.LabelFrame(main_frame, text="File Selection", padding="5")
        file_frame.grid(row=0, column=0, columnspan=2, sticky=(tk.W, tk.E), pady=(0, 10))
        
        ttk.Label(file_frame, text="Excel File:").grid(row=0, column=0, sticky=tk.W, padx=(0, 5))
        self.file_path_var = tk.StringVar()
        self.file_entry = ttk.Entry(file_frame, textvariable=self.file_path_var, width=50)
        self.file_entry.grid(row=0, column=1, sticky=(tk.W, tk.E), padx=(0, 5))
        
        ttk.Button(file_frame, text="Browse", command=self.browse_file).grid(row=0, column=2, padx=(0, 5))
        ttk.Button(file_frame, text="Load File", command=self.load_excel_file).grid(row=0, column=3)
        
        file_frame.columnconfigure(1, weight=1)
        
        # Control frame
        control_frame = ttk.LabelFrame(main_frame, text="Controls", padding="5")
        control_frame.grid(row=1, column=0, columnspan=2, sticky=(tk.W, tk.E), pady=(0, 10))
        
        # Sheet selection
        ttk.Label(control_frame, text="Sheet:").grid(row=0, column=0, sticky=tk.W, padx=(0, 5))
        self.sheet_var = tk.StringVar()
        self.sheet_combo = ttk.Combobox(control_frame, textvariable=self.sheet_var, state="readonly", width=20)
        self.sheet_combo.grid(row=0, column=1, sticky=tk.W, padx=(0, 20))
        self.sheet_combo.bind("<<ComboboxSelected>>", self.on_sheet_change)
        
        # Cell selection
        ttk.Label(control_frame, text="Cell Range:").grid(row=0, column=2, sticky=tk.W, padx=(0, 5))
        self.cell_range_var = tk.StringVar()
        self.cell_entry = ttk.Entry(control_frame, textvariable=self.cell_range_var, width=15)
        self.cell_entry.grid(row=0, column=3, sticky=tk.W, padx=(0, 20))
        self.cell_entry.insert(0, "A1:Z50")
        
        # Page setup
        ttk.Label(control_frame, text="Page Size:").grid(row=0, column=4, sticky=tk.W, padx=(0, 5))
        self.page_size_var = tk.StringVar(value="A4")
        page_size_combo = ttk.Combobox(control_frame, textvariable=self.page_size_var, 
                                      values=["A4", "A3", "Letter", "Legal"], state="readonly", width=10)
        page_size_combo.grid(row=0, column=5, sticky=tk.W, padx=(0, 20))
        
        # Orientation
        ttk.Label(control_frame, text="Orientation:").grid(row=0, column=6, sticky=tk.W, padx=(0, 5))
        self.orientation_var = tk.StringVar(value="Portrait")
        orientation_combo = ttk.Combobox(control_frame, textvariable=self.orientation_var,
                                       values=["Portrait", "Landscape"], state="readonly", width=10)
        orientation_combo.grid(row=0, column=7, sticky=tk.W, padx=(0, 20))
        
        # Buttons
        ttk.Button(control_frame, text="Preview", command=self.preview_excel).grid(row=0, column=8, padx=(0, 5))
        ttk.Button(control_frame, text="Convert to PDF", command=self.convert_to_pdf).grid(row=0, column=9)
        
        # Preview frame
        preview_frame = ttk.LabelFrame(main_frame, text="Excel Preview", padding="5")
        preview_frame.grid(row=2, column=0, columnspan=2, sticky=(tk.W, tk.E, tk.N, tk.S), pady=(0, 10))
        
        # Create canvas for preview
        self.canvas = tk.Canvas(preview_frame, bg="white", relief=tk.SUNKEN, bd=1)
        self.canvas.grid(row=0, column=0, sticky=(tk.W, tk.E, tk.N, tk.S))
        
        # Scrollbars
        v_scrollbar = ttk.Scrollbar(preview_frame, orient=tk.VERTICAL, command=self.canvas.yview)
        v_scrollbar.grid(row=0, column=1, sticky=(tk.N, tk.S))
        h_scrollbar = ttk.Scrollbar(preview_frame, orient=tk.HORIZONTAL, command=self.canvas.xview)
        h_scrollbar.grid(row=1, column=0, sticky=(tk.W, tk.E))
        
        self.canvas.configure(yscrollcommand=v_scrollbar.set, xscrollcommand=h_scrollbar.set)
        
        preview_frame.columnconfigure(0, weight=1)
        preview_frame.rowconfigure(0, weight=1)
        
        # Status bar
        self.status_var = tk.StringVar(value="Ready")
        status_bar = ttk.Label(main_frame, textvariable=self.status_var, relief=tk.SUNKEN, anchor=tk.W)
        status_bar.grid(row=3, column=0, columnspan=2, sticky=(tk.W, tk.E))
        
    def browse_file(self):
        filename = filedialog.askopenfilename(
            title="Select Excel File",
            filetypes=[("Excel files", "*.xlsx *.xls"), ("All files", "*.*")]
        )
        if filename:
            self.file_path_var.set(filename)
            
    def load_excel_file(self):
        if not self.file_path_var.get():
            messagebox.showerror("Error", "Please select an Excel file first")
            return
            
        try:
            self.status_var.set("Loading Excel file...")
            self.root.update()
            
            # Initialize Excel application
            self.excel_app = win32com.client.Dispatch("Excel.Application")
            self.excel_app.Visible = False
            
            # Open workbook
            self.workbook = self.excel_app.Workbooks.Open(self.file_path_var.get())
            
            # Populate sheet combo
            sheet_names = [sheet.Name for sheet in self.workbook.Sheets]
            self.sheet_combo['values'] = sheet_names
            if sheet_names:
                self.sheet_combo.set(sheet_names[0])
                self.current_sheet = self.workbook.Sheets(sheet_names[0])
                
            self.status_var.set(f"Loaded: {os.path.basename(self.file_path_var.get())}")
            
        except Exception as e:
            messagebox.showerror("Error", f"Failed to load Excel file: {str(e)}")
            self.status_var.set("Error loading file")
            
    def on_sheet_change(self, event=None):
        if self.workbook and self.sheet_var.get():
            try:
                self.current_sheet = self.workbook.Sheets(self.sheet_var.get())
                self.status_var.set(f"Switched to sheet: {self.sheet_var.get()}")
            except Exception as e:
                messagebox.showerror("Error", f"Failed to switch sheet: {str(e)}")
                
    def preview_excel(self):
        if not self.current_sheet:
            messagebox.showerror("Error", "Please load an Excel file and select a sheet first")
            return
            
        try:
            self.status_var.set("Generating preview...")
            self.root.update()
            
            # Get cell range
            cell_range = self.cell_range_var.get()
            if not cell_range:
                cell_range = "A1:Z50"
                
            # Get data from Excel
            data_range = self.current_sheet.Range(cell_range)
            data = []
            
            for row in data_range.Rows:
                row_data = []
                for cell in row.Cells:
                    value = cell.Value
                    if value is None:
                        value = ""
                    row_data.append(str(value))
                data.append(row_data)
                
            # Create preview in canvas
            self.display_preview(data)
            
            self.status_var.set("Preview generated successfully")
            
        except Exception as e:
            messagebox.showerror("Error", f"Failed to generate preview: {str(e)}")
            self.status_var.set("Error generating preview")
            
    def display_preview(self, data):
        # Clear canvas
        self.canvas.delete("all")
        
        if not data:
            return
            
        # Calculate cell dimensions
        cell_width = 100
        cell_height = 25
        header_height = 30
        
        # Calculate total dimensions
        total_width = len(data[0]) * cell_width
        total_height = len(data) * cell_height + header_height
        
        # Configure canvas scroll region
        self.canvas.configure(scrollregion=(0, 0, total_width, total_height))
        
        # Draw headers
        for i, col in enumerate(range(ord('A'), ord('A') + len(data[0]))):
            x = i * cell_width
            self.canvas.create_rectangle(x, 0, x + cell_width, header_height, fill="lightgray", outline="black")
            self.canvas.create_text(x + cell_width//2, header_height//2, text=chr(col), font=("Arial", 10, "bold"))
            
        # Draw data
        for row_idx, row_data in enumerate(data):
            y = header_height + row_idx * cell_height
            for col_idx, cell_value in enumerate(row_data):
                x = col_idx * cell_width
                
                # Create cell rectangle
                self.canvas.create_rectangle(x, y, x + cell_width, y + cell_height, fill="white", outline="gray")
                
                # Add cell text (truncate if too long)
                display_text = str(cell_value)[:15] + "..." if len(str(cell_value)) > 15 else str(cell_value)
                self.canvas.create_text(x + 5, y + cell_height//2, text=display_text, 
                                      anchor=tk.W, font=("Arial", 8))
                
    def convert_to_pdf(self):
        if not self.workbook:
            messagebox.showerror("Error", "Please load an Excel file first")
            return
            
        try:
            # Get output file path
            output_file = filedialog.asksaveasfilename(
                title="Save PDF As",
                defaultextension=".pdf",
                filetypes=[("PDF files", "*.pdf"), ("All files", "*.*")]
            )
            
            if not output_file:
                return
                
            self.status_var.set("Converting to PDF...")
            self.root.update()
            
            # Apply page setup if specified
            if self.current_sheet:
                # Set page size
                page_size_map = {
                    "A4": 7,  # xlPaperA4
                    "A3": 8,  # xlPaperA3
                    "Letter": 1,  # xlPaperLetter
                    "Legal": 5   # xlPaperLegal
                }
                
                if self.page_size_var.get() in page_size_map:
                    self.current_sheet.PageSetup.PaperSize = page_size_map[self.page_size_var.get()]
                
                # Set orientation
                if self.orientation_var.get() == "Landscape":
                    self.current_sheet.PageSetup.Orientation = 2  # xlLandscape
                else:
                    self.current_sheet.PageSetup.Orientation = 1  # xlPortrait
                    
                # Set print area if cell range is specified
                cell_range = self.cell_range_var.get()
                if cell_range and cell_range != "A1:Z50":
                    self.current_sheet.PageSetup.PrintArea = cell_range
            
            # Convert to PDF
            self.workbook.ExportAsFixedFormat(0, output_file)
            
            self.status_var.set(f"PDF saved: {os.path.basename(output_file)}")
            messagebox.showinfo("Success", f"PDF has been saved to:\n{output_file}")
            
        except Exception as e:
            messagebox.showerror("Error", f"Failed to convert to PDF: {str(e)}")
            self.status_var.set("Error converting to PDF")
            
    def on_closing(self):
        try:
            if self.workbook:
                self.workbook.Close(False)
            if self.excel_app:
                self.excel_app.Quit()
        except:
            pass
        self.root.destroy()

def main():
    root = tk.Tk()
    app = ExcelPreviewConverter(root)
    root.protocol("WM_DELETE_WINDOW", app.on_closing)
    root.mainloop()

if __name__ == "__main__":
    main()