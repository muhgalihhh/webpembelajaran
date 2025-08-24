// Excel Preview & PDF Converter - JavaScript

class ExcelPreviewApp {
    constructor() {
        this.currentSheet = null;
        this.currentData = null;
        this.zoomLevel = 1;
        this.init();
    }

    init() {
        this.bindEvents();
        this.updateStatus('Ready');
    }

    bindEvents() {
        // File upload
        document.getElementById('uploadForm').addEventListener('submit', (e) => {
            e.preventDefault();
            this.uploadFile();
        });

        // Sheet selection
        document.getElementById('sheetSelect').addEventListener('change', (e) => {
            this.loadSheet(e.target.value);
        });

        // Load range
        document.getElementById('loadRangeBtn').addEventListener('click', () => {
            this.loadRange();
        });

        // Convert to PDF
        document.getElementById('convertBtn').addEventListener('click', () => {
            this.convertToPDF();
        });

        // Zoom controls
        document.getElementById('zoomInBtn').addEventListener('click', () => {
            this.zoomIn();
        });

        document.getElementById('zoomOutBtn').addEventListener('click', () => {
            this.zoomOut();
        });

        document.getElementById('resetZoomBtn').addEventListener('click', () => {
            this.resetZoom();
        });
    }

    async uploadFile() {
        const formData = new FormData(document.getElementById('uploadForm'));
        const fileInput = document.getElementById('excelFile');
        
        if (!fileInput.files[0]) {
            this.showError('Please select a file');
            return;
        }

        this.showLoading('Uploading file...');
        this.updateStatus('Uploading file...');

        try {
            const response = await fetch('/upload', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                this.updateStatus(`File loaded: ${result.filename}`);
                this.populateSheets(result.sheets);
                this.showSheets();
                
                // Auto-load first sheet
                if (result.first_sheet) {
                    document.getElementById('sheetSelect').value = result.first_sheet;
                    this.loadSheet(result.first_sheet);
                }
            } else {
                this.showError(result.error);
            }
        } catch (error) {
            this.showError('Upload failed: ' + error.message);
        } finally {
            this.hideLoading();
        }
    }

    populateSheets(sheets) {
        const select = document.getElementById('sheetSelect');
        select.innerHTML = '';
        
        sheets.forEach(sheet => {
            const option = document.createElement('option');
            option.value = sheet;
            option.textContent = sheet;
            select.appendChild(option);
        });
    }

    showSheets() {
        document.getElementById('sheetCard').style.display = 'block';
        document.getElementById('rangeCard').style.display = 'block';
        document.getElementById('pdfCard').style.display = 'block';
    }

    async loadSheet(sheetName) {
        if (!sheetName) return;

        this.currentSheet = sheetName;
        this.showLoading('Loading sheet...');
        this.updateStatus(`Loading sheet: ${sheetName}`);

        try {
            const response = await fetch(`/api/sheet/${encodeURIComponent(sheetName)}`);
            const data = await response.json();

            if (data.error) {
                this.showError(data.error);
                return;
            }

            this.currentData = data;
            this.renderExcelTable(data);
            this.updateStatus(`Sheet loaded: ${sheetName} (${data.max_row} rows, ${data.max_col} columns)`);
        } catch (error) {
            this.showError('Failed to load sheet: ' + error.message);
        } finally {
            this.hideLoading();
        }
    }

    async loadRange() {
        const range = document.getElementById('cellRange').value;
        if (!range || !this.currentSheet) return;

        this.showLoading('Loading range...');
        this.updateStatus(`Loading range: ${range}`);

        try {
            // Parse range (e.g., "A1:Z50")
            const [start, end] = range.split(':');
            const startCol = this.columnToNumber(start.replace(/\d/g, ''));
            const startRow = parseInt(start.replace(/\D/g, ''));
            const endCol = this.columnToNumber(end.replace(/\d/g, ''));
            const endRow = parseInt(end.replace(/\D/g, ''));

            const response = await fetch(`/api/sheet/${encodeURIComponent(this.currentSheet)}?start_row=${startRow}&end_row=${endRow}&start_col=${startCol}&end_col=${endCol}`);
            const data = await response.json();

            if (data.error) {
                this.showError(data.error);
                return;
            }

            this.currentData = data;
            this.renderExcelTable(data);
            this.updateStatus(`Range loaded: ${range}`);
        } catch (error) {
            this.showError('Failed to load range: ' + error.message);
        } finally {
            this.hideLoading();
        }
    }

    columnToNumber(column) {
        let result = 0;
        for (let i = 0; i < column.length; i++) {
            result *= 26;
            result += column.charCodeAt(i) - 'A'.charCodeAt(0) + 1;
        }
        return result;
    }

    renderExcelTable(data) {
        const container = document.getElementById('excelTable');
        
        if (!data.data || data.data.length === 0) {
            container.innerHTML = '<div class="text-center text-muted py-5"><p>No data found</p></div>';
            return;
        }

        let html = '<table class="table table-sm table-bordered mb-0">';
        
        // Create header row with column letters
        html += '<thead><tr><th></th>'; // Empty cell for row numbers
        for (let col = 0; col < data.data[0].length; col++) {
            const colLetter = this.numberToColumn(col + 1);
            html += `<th>${colLetter}</th>`;
        }
        html += '</tr></thead>';

        // Create data rows
        html += '<tbody>';
        data.data.forEach((row, rowIndex) => {
            html += `<tr><th class="text-center bg-light">${rowIndex + 1}</th>`;
            
            row.forEach((cell, colIndex) => {
                const cellHtml = this.renderCell(cell, rowIndex, colIndex);
                html += cellHtml;
            });
            
            html += '</tr>';
        });
        html += '</tbody></table>';

        container.innerHTML = html;
        container.classList.add('fade-in');
    }

    renderCell(cell, rowIndex, colIndex) {
        if (!cell || cell.value === null || cell.value === undefined) {
            return '<td></td>';
        }

        let classes = [];
        let styles = [];
        let content = this.formatCellValue(cell);

        // Apply font styles
        if (cell.styles.font) {
            const font = cell.styles.font;
            if (font.bold) classes.push('cell-bold');
            if (font.italic) classes.push('cell-italic');
            if (font.underline) classes.push('cell-underline');
            if (font.color) styles.push(`color: #${font.color.substring(2)}`);
            if (font.size) styles.push(`font-size: ${font.size}px`);
        }

        // Apply alignment
        if (cell.styles.alignment) {
            const align = cell.styles.alignment;
            if (align.horizontal === 'center') classes.push('cell-center');
            else if (align.horizontal === 'right') classes.push('cell-right');
            else if (align.horizontal === 'left') classes.push('cell-left');
            
            if (align.vertical === 'top') classes.push('cell-top');
            else if (align.vertical === 'bottom') classes.push('cell-bottom');
        }

        // Apply background color
        if (cell.styles.fill && cell.styles.fill.color) {
            styles.push(`background-color: #${cell.styles.fill.color.substring(2)}`);
        }

        // Apply borders
        if (cell.styles.border) {
            const border = cell.styles.border;
            Object.keys(border).forEach(side => {
                const borderInfo = border[side];
                if (borderInfo.style) {
                    const borderStyle = this.getBorderStyle(borderInfo.style);
                    const borderColor = borderInfo.color ? `#${borderInfo.color.substring(2)}` : '#000';
                    styles.push(`border-${side}: 1px ${borderStyle} ${borderColor}`);
                }
            });
        }

        // Apply number format
        if (cell.styles.number_format) {
            const format = cell.styles.number_format;
            if (format.includes('$') || format.includes('€') || format.includes('£')) {
                classes.push('currency-format');
            } else if (format.includes('dd') || format.includes('mm') || format.includes('yyyy')) {
                classes.push('date-format');
            } else if (format.includes('#') || format.includes('0')) {
                classes.push('number-format');
            }
        }

        const classAttr = classes.length > 0 ? ` class="${classes.join(' ')}"` : '';
        const styleAttr = styles.length > 0 ? ` style="${styles.join('; ')}"` : '';
        const titleAttr = content.length > 20 ? ` title="${content}"` : '';

        return `<td${classAttr}${styleAttr}${titleAttr}>${content}</td>`;
    }

    formatCellValue(cell) {
        let value = cell.value;
        
        if (value === null || value === undefined) {
            return '';
        }

        // Handle different data types
        if (typeof value === 'number') {
            if (cell.styles.number_format) {
                // Apply number formatting
                return this.formatNumber(value, cell.styles.number_format);
            }
            return value.toString();
        }

        if (typeof value === 'boolean') {
            return value ? 'TRUE' : 'FALSE';
        }

        if (value instanceof Date) {
            return value.toLocaleDateString();
        }

        return value.toString();
    }

    formatNumber(value, format) {
        // Simple number formatting
        if (format.includes('$')) {
            return '$' + value.toLocaleString();
        }
        if (format.includes('€')) {
            return '€' + value.toLocaleString();
        }
        if (format.includes('£')) {
            return '£' + value.toLocaleString();
        }
        if (format.includes('#')) {
            return value.toLocaleString();
        }
        if (format.includes('0.00')) {
            return value.toFixed(2);
        }
        
        return value.toString();
    }

    getBorderStyle(style) {
        const styleMap = {
            'thin': 'solid',
            'medium': 'solid',
            'thick': 'solid',
            'dashed': 'dashed',
            'dotted': 'dotted',
            'double': 'double'
        };
        return styleMap[style] || 'solid';
    }

    numberToColumn(num) {
        let result = '';
        while (num > 0) {
            num--;
            result = String.fromCharCode(65 + (num % 26)) + result;
            num = Math.floor(num / 26);
        }
        return result;
    }

    async convertToPDF() {
        if (!this.currentSheet) {
            this.showError('Please select a sheet first');
            return;
        }

        const range = document.getElementById('cellRange').value;
        const pageSize = document.getElementById('pageSize').value;
        const orientation = document.getElementById('orientation').value;

        this.showLoading('Converting to PDF...');
        this.updateStatus('Converting to PDF...');

        try {
            const response = await fetch('/convert-pdf', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    sheet_name: this.currentSheet,
                    cell_range: range,
                    page_size: pageSize,
                    orientation: orientation
                })
            });

            const result = await response.json();

            if (result.success) {
                this.showSuccess(result.message, result.pdf_file);
                this.updateStatus('PDF converted successfully');
            } else {
                this.showError(result.error);
            }
        } catch (error) {
            this.showError('Conversion failed: ' + error.message);
        } finally {
            this.hideLoading();
        }
    }

    zoomIn() {
        this.zoomLevel = Math.min(this.zoomLevel * 1.2, 3);
        this.applyZoom();
    }

    zoomOut() {
        this.zoomLevel = Math.max(this.zoomLevel / 1.2, 0.3);
        this.applyZoom();
    }

    resetZoom() {
        this.zoomLevel = 1;
        this.applyZoom();
    }

    applyZoom() {
        const container = document.getElementById('excelTable');
        container.style.transform = `scale(${this.zoomLevel})`;
        container.style.transformOrigin = 'top left';
    }

    showLoading(message) {
        document.getElementById('loadingText').textContent = message;
        const modal = new bootstrap.Modal(document.getElementById('loadingModal'));
        modal.show();
    }

    hideLoading() {
        const modal = bootstrap.Modal.getInstance(document.getElementById('loadingModal'));
        if (modal) {
            modal.hide();
        }
    }

    showSuccess(message, pdfFile) {
        document.getElementById('successMessage').textContent = message;
        document.getElementById('downloadLink').href = `/download/${pdfFile}`;
        const modal = new bootstrap.Modal(document.getElementById('successModal'));
        modal.show();
    }

    showError(message) {
        this.updateStatus(message, 'error');
        // You could add a toast notification here
        alert(message);
    }

    updateStatus(message, type = 'info') {
        const statusElement = document.getElementById('statusText');
        statusElement.textContent = message;
        statusElement.className = `status-${type}`;
    }
}

// Initialize app when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new ExcelPreviewApp();
});