<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ExcelPreviewService
{
    public function readExcelWithStyles($filePath, $sheetName = null, $range = null)
    {
        try {
            // Load the Excel file
            $spreadsheet = IOFactory::load($filePath);
            
            // Get the active sheet or specified sheet
            if ($sheetName) {
                $worksheet = $spreadsheet->getSheetByName($sheetName);
                if (!$worksheet) {
                    throw new \Exception("Sheet '$sheetName' not found");
                }
            } else {
                $worksheet = $spreadsheet->getActiveSheet();
            }
            
            // Determine the range to read
            if ($range) {
                $rangeData = $this->parseRange($range);
                $startCell = $rangeData['start'];
                $endCell = $rangeData['end'];
            } else {
                // Get the highest row and column
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();
                $startCell = 'A1';
                $endCell = $highestColumn . $highestRow;
            }
            
            // Get the range coordinates
            $startColumn = Coordinate::columnIndexFromString($startCell[0]);
            $startRow = (int) substr($startCell, 1);
            $endColumn = Coordinate::columnIndexFromString($endCell[0]);
            $endRow = (int) substr($endCell, 1);
            
            $data = [];
            $styles = [];
            $mergedCells = [];
            $columnWidths = [];
            $rowHeights = [];
            
            // Get merged cells
            foreach ($worksheet->getMergeCells() as $mergeCell) {
                $mergedCells[] = $mergeCell;
            }
            
            // Get column widths
            for ($col = $startColumn; $col <= $endColumn; $col++) {
                $columnLetter = Coordinate::stringFromColumnIndex($col);
                $columnWidths[$columnLetter] = $worksheet->getColumnDimension($columnLetter)->getWidth() ?: 10;
            }
            
            // Get row heights
            for ($row = $startRow; $row <= $endRow; $row++) {
                $rowHeights[$row] = $worksheet->getRowDimension($row)->getRowHeight() ?: 15;
            }
            
            // Read data and styles
            for ($row = $startRow; $row <= $endRow; $row++) {
                $rowData = [];
                $rowStyles = [];
                
                for ($col = $startColumn; $col <= $endColumn; $col++) {
                    $columnLetter = Coordinate::stringFromColumnIndex($col);
                    $cellCoordinate = $columnLetter . $row;
                    
                    // Get cell value
                    $cell = $worksheet->getCell($cellCoordinate);
                    $value = $cell->getValue();
                    
                    // Handle different data types
                    if ($cell->getDataType() == DataType::TYPE_NUMERIC) {
                        if (Date::isDateTime($cell)) {
                            $value = Date::excelToDateTimeObject($value)->format('Y-m-d H:i:s');
                        } else {
                            $value = (string) $value;
                        }
                    } elseif ($cell->getDataType() == DataType::TYPE_BOOL) {
                        $value = $value ? 'TRUE' : 'FALSE';
                    } else {
                        $value = (string) $value;
                    }
                    
                    $rowData[] = $value;
                    
                    // Get cell style
                    $style = $cell->getStyle();
                    $rowStyles[] = $this->extractCellStyle($style);
                }
                
                $data[] = $rowData;
                $styles[] = $rowStyles;
            }
            
            return [
                'data' => $data,
                'styles' => $styles,
                'mergedCells' => $mergedCells,
                'columnWidths' => $columnWidths,
                'rowHeights' => $rowHeights,
                'sheetName' => $worksheet->getTitle(),
                'range' => $startCell . ':' . $endCell
            ];
            
        } catch (\Exception $e) {
            throw new \Exception("Error reading Excel file: " . $e->getMessage());
        }
    }
    
    private function parseRange($range)
    {
        if (strpos($range, ':') !== false) {
            $parts = explode(':', $range);
            return [
                'start' => trim($parts[0]),
                'end' => trim($parts[1])
            ];
        }
        
        return [
            'start' => $range,
            'end' => $range
        ];
    }
    
    private function extractCellStyle($style)
    {
        $extractedStyle = [
            'font' => [],
            'alignment' => [],
            'border' => [],
            'fill' => [],
            'numberFormat' => ''
        ];
        
        // Font properties
        $font = $style->getFont();
        if ($font) {
            $extractedStyle['font'] = [
                'name' => $font->getName(),
                'size' => $font->getSize(),
                'bold' => $font->getBold(),
                'italic' => $font->getItalic(),
                'underline' => $font->getUnderline(),
                'strikethrough' => $font->getStrikethrough(),
                'color' => $this->getColorValue($font->getColor())
            ];
        }
        
        // Alignment properties
        $alignment = $style->getAlignment();
        if ($alignment) {
            $extractedStyle['alignment'] = [
                'horizontal' => $alignment->getHorizontal(),
                'vertical' => $alignment->getVertical(),
                'wrapText' => $alignment->getWrapText(),
                'textRotation' => $alignment->getTextRotation()
            ];
        }
        
        // Border properties
        $borders = $style->getBorders();
        if ($borders) {
            $extractedStyle['border'] = [
                'left' => $this->getBorderStyle($borders->getLeft()),
                'right' => $this->getBorderStyle($borders->getRight()),
                'top' => $this->getBorderStyle($borders->getTop()),
                'bottom' => $this->getBorderStyle($borders->getBottom())
            ];
        }
        
        // Fill properties
        $fill = $style->getFill();
        if ($fill) {
            $extractedStyle['fill'] = [
                'type' => $fill->getFillType(),
                'color' => $this->getColorValue($fill->getStartColor()),
                'endColor' => $this->getColorValue($fill->getEndColor())
            ];
        }
        
        // Number format
        $extractedStyle['numberFormat'] = $style->getNumberFormat()->getFormatCode();
        
        return $extractedStyle;
    }
    
    private function getColorValue($color)
    {
        if (!$color) return null;
        
        $rgb = $color->getRGB();
        if ($rgb) {
            return '#' . $rgb;
        }
        
        return null;
    }
    
    private function getBorderStyle($border)
    {
        if (!$border) return null;
        
        return [
            'style' => $border->getBorderStyle(),
            'color' => $this->getColorValue($border->getColor())
        ];
    }
    
    public function getSheetNames($filePath)
    {
        try {
            $spreadsheet = IOFactory::load($filePath);
            return $spreadsheet->getSheetNames();
        } catch (\Exception $e) {
            throw new \Exception("Error reading sheet names: " . $e->getMessage());
        }
    }
    
    public function generateCssFromStyles($styles, $columnWidths, $rowHeights)
    {
        $css = [];
        
        // Generate CSS for each cell
        foreach ($styles as $rowIndex => $rowStyles) {
            foreach ($rowStyles as $colIndex => $cellStyle) {
                $cellId = "cell-{$rowIndex}-{$colIndex}";
                $cellCss = [];
                
                // Font styles
                if (!empty($cellStyle['font'])) {
                    $font = $cellStyle['font'];
                    if ($font['name']) $cellCss[] = "font-family: '{$font['name']}', sans-serif;";
                    if ($font['size']) $cellCss[] = "font-size: {$font['size']}px;";
                    if ($font['bold']) $cellCss[] = "font-weight: bold;";
                    if ($font['italic']) $cellCss[] = "font-style: italic;";
                    if ($font['underline']) $cellCss[] = "text-decoration: underline;";
                    if ($font['strikethrough']) $cellCss[] = "text-decoration: line-through;";
                    if ($font['color']) $cellCss[] = "color: {$font['color']};";
                }
                
                // Alignment styles
                if (!empty($cellStyle['alignment'])) {
                    $align = $cellStyle['alignment'];
                    if ($align['horizontal']) {
                        $cellCss[] = "text-align: " . strtolower($align['horizontal']) . ";";
                    }
                    if ($align['vertical']) {
                        $cellCss[] = "vertical-align: " . strtolower($align['vertical']) . ";";
                    }
                    if ($align['wrapText']) {
                        $cellCss[] = "white-space: normal; word-wrap: break-word;";
                    }
                }
                
                // Border styles
                if (!empty($cellStyle['border'])) {
                    $border = $cellStyle['border'];
                    foreach (['left', 'right', 'top', 'bottom'] as $side) {
                        if (!empty($border[$side])) {
                            $style = $border[$side];
                            $borderStyle = $this->getBorderStyleCss($style['style']);
                            $color = $style['color'] ?: '#000000';
                            $cellCss[] = "border-{$side}: {$borderStyle} {$color};";
                        }
                    }
                }
                
                // Fill styles
                if (!empty($cellStyle['fill']) && $cellStyle['fill']['color']) {
                    $cellCss[] = "background-color: {$cellStyle['fill']['color']};";
                }
                
                if (!empty($cellCss)) {
                    $css[$cellId] = implode(' ', $cellCss);
                }
            }
        }
        
        return $css;
    }
    
    private function getBorderStyleCss($borderStyle)
    {
        $styleMap = [
            Border::BORDER_NONE => 'none',
            Border::BORDER_DASHDOT => 'dashed',
            Border::BORDER_DASHDOTDOT => 'dashed',
            Border::BORDER_DASHED => 'dashed',
            Border::BORDER_DOTTED => 'dotted',
            Border::BORDER_DOUBLE => 'double',
            Border::BORDER_HAIR => 'solid',
            Border::BORDER_MEDIUM => 'solid',
            Border::BORDER_MEDIUMDASHDOT => 'dashed',
            Border::BORDER_MEDIUMDASHDOTDOT => 'dashed',
            Border::BORDER_MEDIUMDASHED => 'dashed',
            Border::BORDER_SLANTDASHDOT => 'dashed',
            Border::BORDER_THICK => 'solid',
            Border::BORDER_THIN => 'solid'
        ];
        
        return $styleMap[$borderStyle] ?? 'solid';
    }
}