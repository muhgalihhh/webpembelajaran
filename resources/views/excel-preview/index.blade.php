<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Excel Preview dengan Style Asli</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        .excel-table {
            border-collapse: collapse;
            table-layout: fixed;
        }
        
        .excel-cell {
            border: 1px solid #d1d5db;
            padding: 4px 8px;
            min-height: 20px;
            position: relative;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .excel-cell.wrap-text {
            white-space: normal;
            word-wrap: break-word;
        }
        
        .excel-header {
            background-color: #f3f4f6;
            font-weight: bold;
            text-align: center;
            border: 1px solid #d1d5db;
            padding: 8px 4px;
        }
        
        .loading-spinner {
            border: 4px solid #f3f4f6;
            border-top: 4px solid #3b82f6;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .preview-container {
            max-height: 70vh;
            overflow: auto;
            border: 1px solid #d1d5db;
            border-radius: 8px;
        }
        
        .sheet-tab {
            cursor: pointer;
            padding: 8px 16px;
            border: 1px solid #d1d5db;
            border-bottom: none;
            background-color: #f9fafb;
            border-radius: 4px 4px 0 0;
            margin-right: 4px;
        }
        
        .sheet-tab.active {
            background-color: white;
            border-bottom: 1px solid white;
            margin-bottom: -1px;
        }
    </style>
</head>
<body class="bg-gray-50" x-data="excelPreview()">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Excel Preview</h1>
                        <p class="text-gray-600">Tampilkan file Excel dengan style dan format asli</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button @click="downloadFile()" 
                                x-show="currentFile"
                                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                            Download File
                        </button>
                        <button @click="deleteFile()" 
                                x-show="currentFile"
                                class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                            Hapus File
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Upload Section -->
            <div class="bg-white rounded-lg shadow-sm border p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Upload File Excel</h2>
                
                <div class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <input type="file" 
                               @change="handleFileUpload($event)"
                               accept=".xlsx,.xls"
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        
                        <button @click="uploadFile()" 
                                x-show="selectedFile"
                                :disabled="uploading"
                                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                            <span x-show="!uploading">Upload</span>
                            <span x-show="uploading" class="flex items-center">
                                <div class="loading-spinner mr-2"></div>
                                Uploading...
                            </span>
                        </button>
                    </div>
                    
                    <div x-show="uploadError" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                        <span x-text="uploadError"></span>
                    </div>
                    
                    <div x-show="uploadSuccess" class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                        <span x-text="uploadSuccess"></span>
                    </div>
                </div>
            </div>

            <!-- Preview Controls -->
            <div x-show="currentFile" class="bg-white rounded-lg shadow-sm border p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Preview Controls</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Sheet Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sheet</label>
                        <select @change="loadPreview()" 
                                x-model="selectedSheet"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <template x-for="sheet in sheetNames" :key="sheet">
                                <option :value="sheet" x-text="sheet"></option>
                            </template>
                        </select>
                    </div>
                    
                    <!-- Range Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Range (opsional)</label>
                        <input type="text" 
                               x-model="selectedRange"
                               placeholder="A1:Z50"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <!-- Load Preview Button -->
                    <div class="flex items-end">
                        <button @click="loadPreview()" 
                                :disabled="loading"
                                class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                            <span x-show="!loading">Load Preview</span>
                            <span x-show="loading" class="flex items-center justify-center">
                                <div class="loading-spinner mr-2"></div>
                                Loading...
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sheet Tabs -->
            <div x-show="sheetNames.length > 1" class="mb-4">
                <div class="flex space-x-1 overflow-x-auto">
                    <template x-for="sheet in sheetNames" :key="sheet">
                        <div @click="selectSheet(sheet)" 
                             :class="selectedSheet === sheet ? 'sheet-tab active' : 'sheet-tab'"
                             x-text="sheet">
                        </div>
                    </template>
                </div>
            </div>

            <!-- Preview Section -->
            <div x-show="excelData.length > 0" class="bg-white rounded-lg shadow-sm border">
                <div class="p-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Preview: <span x-text="currentSheetName"></span>
                    </h3>
                    <p class="text-sm text-gray-600" x-text="'Range: ' + currentRange"></p>
                </div>
                
                <div class="preview-container">
                    <div class="overflow-auto">
                        <table class="excel-table w-full">
                            <thead>
                                <tr>
                                    <template x-for="(col, colIndex) in excelData[0]" :key="colIndex">
                                        <th class="excel-header" 
                                            :style="'width: ' + (columnWidths[getColumnLetter(colIndex)] || 100) + 'px'"
                                            x-text="getColumnLetter(colIndex)">
                                        </th>
                                    </template>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(row, rowIndex) in excelData" :key="rowIndex">
                                    <tr>
                                        <template x-for="(cell, colIndex) in row" :key="colIndex">
                                            <td class="excel-cell"
                                                :id="'cell-' + rowIndex + '-' + colIndex"
                                                :style="getCellStyle(rowIndex, colIndex)"
                                                x-text="cell">
                                            </td>
                                        </template>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Error Display -->
            <div x-show="error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mt-4">
                <span x-text="error"></span>
            </div>
        </main>
    </div>

    <script>
        function excelPreview() {
            return {
                selectedFile: null,
                currentFile: null,
                uploading: false,
                loading: false,
                uploadError: '',
                uploadSuccess: '',
                error: '',
                sheetNames: [],
                selectedSheet: '',
                selectedRange: '',
                excelData: [],
                excelStyles: [],
                excelCss: {},
                columnWidths: {},
                rowHeights: {},
                currentSheetName: '',
                currentRange: '',
                
                handleFileUpload(event) {
                    this.selectedFile = event.target.files[0];
                    this.uploadError = '';
                    this.uploadSuccess = '';
                },
                
                async uploadFile() {
                    if (!this.selectedFile) return;
                    
                    this.uploading = true;
                    this.uploadError = '';
                    this.uploadSuccess = '';
                    
                    const formData = new FormData();
                    formData.append('excel_file', this.selectedFile);
                    
                    try {
                        const response = await fetch('/excel-preview/upload', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        
                        const result = await response.json();
                        
                        if (result.success) {
                            this.currentFile = result.file_name;
                            this.sheetNames = result.sheet_names;
                            this.selectedSheet = result.sheet_names[0];
                            this.uploadSuccess = result.message;
                            this.currentFile = result.file_path;
                            
                            // Auto load preview
                            this.loadPreview();
                        } else {
                            this.uploadError = result.message || 'Upload failed';
                        }
                    } catch (error) {
                        this.uploadError = 'Network error: ' + error.message;
                    } finally {
                        this.uploading = false;
                    }
                },
                
                async loadPreview() {
                    if (!this.currentFile) return;
                    
                    this.loading = true;
                    this.error = '';
                    
                    try {
                        const response = await fetch('/excel-preview/preview', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                file_path: this.currentFile,
                                sheet_name: this.selectedSheet,
                                range: this.selectedRange
                            })
                        });
                        
                        const result = await response.json();
                        
                        if (result.success) {
                            this.excelData = result.data;
                            this.excelStyles = result.styles;
                            this.excelCss = result.css;
                            this.columnWidths = result.column_widths;
                            this.rowHeights = result.row_heights;
                            this.currentSheetName = result.sheet_name;
                            this.currentRange = result.range;
                            
                            // Apply CSS styles
                            this.applyStyles();
                        } else {
                            this.error = result.message || 'Failed to load preview';
                        }
                    } catch (error) {
                        this.error = 'Network error: ' + error.message;
                    } finally {
                        this.loading = false;
                    }
                },
                
                selectSheet(sheetName) {
                    this.selectedSheet = sheetName;
                    this.loadPreview();
                },
                
                applyStyles() {
                    // Remove existing styles
                    const existingStyle = document.getElementById('excel-dynamic-styles');
                    if (existingStyle) {
                        existingStyle.remove();
                    }
                    
                    // Create new style element
                    const styleElement = document.createElement('style');
                    styleElement.id = 'excel-dynamic-styles';
                    
                    let cssText = '';
                    for (const [cellId, styles] of Object.entries(this.excelCss)) {
                        cssText += `#${cellId} { ${styles} }\n`;
                    }
                    
                    styleElement.textContent = cssText;
                    document.head.appendChild(styleElement);
                },
                
                getColumnLetter(index) {
                    let result = '';
                    while (index >= 0) {
                        result = String.fromCharCode(65 + (index % 26)) + result;
                        index = Math.floor(index / 26) - 1;
                    }
                    return result;
                },
                
                getCellStyle(rowIndex, colIndex) {
                    const cellId = `cell-${rowIndex}-${colIndex}`;
                    const height = this.rowHeights[rowIndex + 1];
                    return height ? `height: ${height}px;` : '';
                },
                
                async downloadFile() {
                    if (!this.currentFile) return;
                    
                    const fileName = this.currentFile.split('/').pop();
                    window.open(`/excel-preview/download/${fileName}`, '_blank');
                },
                
                async deleteFile() {
                    if (!this.currentFile) return;
                    
                    if (!confirm('Are you sure you want to delete this file?')) return;
                    
                    const fileName = this.currentFile.split('/').pop();
                    
                    try {
                        const response = await fetch(`/excel-preview/delete/${fileName}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        
                        const result = await response.json();
                        
                        if (result.success) {
                            this.currentFile = null;
                            this.excelData = [];
                            this.sheetNames = [];
                            this.selectedFile = null;
                            this.uploadSuccess = 'File deleted successfully';
                        }
                    } catch (error) {
                        this.error = 'Error deleting file: ' + error.message;
                    }
                }
            }
        }
    </script>
</body>
</html>