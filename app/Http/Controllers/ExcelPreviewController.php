<?php

namespace App\Http\Controllers;

use App\Services\ExcelPreviewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ExcelPreviewController extends Controller
{
    protected $excelService;
    
    public function __construct(ExcelPreviewService $excelService)
    {
        $this->excelService = $excelService;
    }
    
    public function index()
    {
        return view('excel-preview.index');
    }
    
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'excel_file' => 'required|file|mimes:xlsx,xls|max:10240', // 10MB max
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            $file = $request->file('excel_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('excel-files', $fileName, 'public');
            
            // Get sheet names
            $fullPath = Storage::disk('public')->path($filePath);
            $sheetNames = $this->excelService->getSheetNames($fullPath);
            
            return response()->json([
                'success' => true,
                'file_path' => $filePath,
                'file_name' => $fileName,
                'sheet_names' => $sheetNames,
                'message' => 'File uploaded successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error uploading file: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function preview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file_path' => 'required|string',
            'sheet_name' => 'nullable|string',
            'range' => 'nullable|string|regex:/^[A-Z]+\d+:[A-Z]+\d+$/'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            $filePath = $request->input('file_path');
            $sheetName = $request->input('sheet_name');
            $range = $request->input('range');
            
            // Check if file exists
            if (!Storage::disk('public')->exists($filePath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File not found'
                ], 404);
            }
            
            $fullPath = Storage::disk('public')->path($filePath);
            
            // Read Excel with styles
            $excelData = $this->excelService->readExcelWithStyles($fullPath, $sheetName, $range);
            
            // Generate CSS from styles
            $css = $this->excelService->generateCssFromStyles(
                $excelData['styles'],
                $excelData['columnWidths'],
                $excelData['rowHeights']
            );
            
            return response()->json([
                'success' => true,
                'data' => $excelData['data'],
                'styles' => $excelData['styles'],
                'css' => $css,
                'merged_cells' => $excelData['mergedCells'],
                'column_widths' => $excelData['columnWidths'],
                'row_heights' => $excelData['rowHeights'],
                'sheet_name' => $excelData['sheetName'],
                'range' => $excelData['range']
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error reading Excel file: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function download($fileName)
    {
        $filePath = 'excel-files/' . $fileName;
        
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found');
        }
        
        return Storage::disk('public')->download($filePath);
    }
    
    public function delete($fileName)
    {
        $filePath = 'excel-files/' . $fileName;
        
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'File deleted successfully'
        ]);
    }
}