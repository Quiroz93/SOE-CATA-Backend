<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\Log;

class DynamicExcelAnalyzerService
{
    /**
     * Analiza un archivo Excel de forma dinámica
     * Identifica encabezados, columnas, tipos de datos, etc.
     *
     * @param string $filePath Ruta del archivo Excel
     * @return array Análisis completo del archivo
     */
    public function analyzeExcelFile(string $filePath): array
    {
        Log::info('DynamicExcelAnalyzer: Iniciando análisis de archivo', [
            'file_path' => $filePath,
            'file_exists' => file_exists($filePath),
            'file_size' => file_exists($filePath) ? filesize($filePath) : 0
        ]);

        try {
            $spreadsheet = IOFactory::load($filePath);
            $sheets = [];
            $totalSheets = $spreadsheet->getSheetCount();

            Log::info('DynamicExcelAnalyzer: Archivo cargado exitosamente', [
                'total_sheets' => $totalSheets
            ]);

            // Analizar cada hoja del archivo
            for ($i = 0; $i < $totalSheets; $i++) {
                Log::info('DynamicExcelAnalyzer: Analizando hoja', ['sheet_index' => $i]);
                
                $sheet = $spreadsheet->getSheet($i);
                $sheetAnalysis = $this->analyzeSheet($sheet, $i);
                
                Log::info('DynamicExcelAnalyzer: Hoja analizada', [
                    'sheet_index' => $i,
                    'sheet_name' => $sheetAnalysis['sheet_name'],
                    'row_count' => $sheetAnalysis['row_count'],
                    'column_count' => $sheetAnalysis['column_count']
                ]);
                
                if ($sheetAnalysis['row_count'] > 0) {
                    $sheets[] = $sheetAnalysis;
                }
            }

            $result = [
                'success' => true,
                'file_name' => basename($filePath),
                'total_sheets' => $totalSheets,
                'sheets' => $sheets,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ];

            Log::info('DynamicExcelAnalyzer: Análisis completado exitosamente', [
                'total_sheets_analyzed' => count($sheets),
                'file_name' => basename($filePath)
            ]);

            return $result;

        } catch (\Exception $e) {
            Log::error('DynamicExcelAnalyzer: Error al analizar archivo', [
                'file_path' => $filePath,
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'error' => 'Error al analizar el archivo: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Analiza una hoja específica del Excel
     *
     * @param Worksheet $sheet
     * @param int $sheetIndex
     * @return array
     */
    private function analyzeSheet(Worksheet $sheet, int $sheetIndex): array
    {
        $sheetName = $sheet->getTitle();
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

        Log::debug('DynamicExcelAnalyzer: Detalles de hoja', [
            'sheet_index' => $sheetIndex,
            'sheet_name' => $sheetName,
            'highest_row' => $highestRow,
            'highest_column' => $highestColumn,
            'column_index' => $highestColumnIndex
        ]);

        // Leer todas las celdas de la hoja
        $allData = [];
        for ($row = 1; $row <= min($highestRow, 1000); $row++) { // Limitar a 1000 filas para análisis
            $rowData = [];
            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $coordinate = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . $row;
                $cell = $sheet->getCell($coordinate);
                $value = $cell->getValue();
                $rowData[] = $value;
            }
            $allData[] = $rowData;
        }

        // Identificar estructura del archivo
        $structure = $this->identifyStructure($allData);
        
        Log::debug('DynamicExcelAnalyzer: Estructura identificada', [
            'sheet_name' => $sheetName,
            'header_row' => $structure['header_row'],
            'has_metadata' => $structure['has_metadata']
        ]);

        // Identificar metadatos (filas antes de la tabla principal)
        $metadata = $this->extractMetadata($allData, $structure['header_row']);
        
        Log::debug('DynamicExcelAnalyzer: Metadatos extraídos', [
            'sheet_name' => $sheetName,
            'metadata_count' => count($metadata)
        ]);

        // Extraer información de columnas
        $columns = $this->analyzeColumns($allData, $structure['header_row'], $highestRow);
        
        Log::debug('DynamicExcelAnalyzer: Columnas analizadas', [
            'sheet_name' => $sheetName,
            'columns_count' => count($columns)
        ]);

        // Obtener muestra de datos
        $dataSample = $this->getDataSample($allData, $structure['header_row'], 10);

        return [
            'sheet_index' => $sheetIndex,
            'sheet_name' => $sheetName,
            'row_count' => $highestRow,
            'column_count' => $highestColumnIndex,
            'metadata' => $metadata,
            'structure' => $structure,
            'columns' => $columns,
            'data_sample' => $dataSample,
            'data_rows_count' => $highestRow - $structure['header_row'],
        ];
    }

    /**
     * Identifica la estructura del archivo (dónde comienza la tabla de datos)
     *
     * @param array $data
     * @return array
     */
    private function identifyStructure(array $data): array
    {
        $headerRow = 0;
        $maxFilledCells = 0;

        // Buscar la fila que probablemente sea el encabezado
        // (la que tenga más celdas no vacías)
        foreach ($data as $rowIndex => $row) {
            $filledCells = count(array_filter($row, function($cell) {
                return !empty($cell);
            }));

            if ($filledCells > $maxFilledCells && $filledCells >= 2) {
                $maxFilledCells = $filledCells;
                $headerRow = $rowIndex;
            }
        }

        // Verificar si hay filas con metadatos antes del encabezado
        $hasMetadata = $headerRow > 0;

        return [
            'header_row' => $headerRow,
            'has_metadata' => $hasMetadata,
            'metadata_rows' => $headerRow,
        ];
    }

    /**
     * Extrae metadatos de las filas antes de la tabla principal
     *
     * @param array $data
     * @param int $headerRow
     * @return array
     */
    private function extractMetadata(array $data, int $headerRow): array
    {
        $metadata = [];

        // Extraer filas antes del encabezado como metadatos
        for ($i = 0; $i < $headerRow; $i++) {
            $row = $data[$i];
            $nonEmptyCells = array_filter($row, function($cell) {
                return !empty($cell);
            });

            if (count($nonEmptyCells) > 0) {
                // Si tiene 2 celdas, probablemente sea clave-valor
                if (count($nonEmptyCells) >= 2) {
                    $cells = array_values($nonEmptyCells);
                    $metadata[] = [
                        'type' => 'key_value',
                        'key' => $cells[0],
                        'value' => $cells[1],
                        'row' => $i + 1
                    ];
                } else {
                    // Sino, es un título o encabezado
                    $metadata[] = [
                        'type' => 'title',
                        'value' => reset($nonEmptyCells),
                        'row' => $i + 1
                    ];
                }
            }
        }

        return $metadata;
    }

    /**
     * Analiza las columnas de datos
     *
     * @param array $data
     * @param int $headerRow
     * @param int $totalRows
     * @return array
     */
    private function analyzeColumns(array $data, int $headerRow, int $totalRows): array
    {
        if (!isset($data[$headerRow])) {
            return [];
        }

        $headers = $data[$headerRow];
        $columns = [];

        foreach ($headers as $colIndex => $header) {
            if (empty($header)) {
                continue;
            }

            // Analizar valores de esta columna
            $values = [];
            $numericCount = 0;
            $textCount = 0;
            $emptyCount = 0;
            $uniqueValues = [];

            for ($row = $headerRow + 1; $row < count($data) && $row < $headerRow + 101; $row++) {
                if (!isset($data[$row][$colIndex])) {
                    continue;
                }

                $value = $data[$row][$colIndex];

                if ($value === null || $value === '') {
                    $emptyCount++;
                    continue;
                }

                $values[] = $value;

                if (is_numeric($value)) {
                    $numericCount++;
                } else {
                    $textCount++;
                }

                // Recopilar valores únicos (limitar a 50)
                if (count($uniqueValues) < 50) {
                    $uniqueValues[$value] = true;
                }
            }

            $totalValues = count($values);
            $dataType = $numericCount > $textCount ? 'numeric' : 'text';

            // Estadísticas para columnas numéricas
            $stats = [];
            if ($dataType === 'numeric' && $totalValues > 0) {
                $numericValues = array_filter($values, 'is_numeric');
                if (count($numericValues) > 0) {
                    $stats = [
                        'min' => min($numericValues),
                        'max' => max($numericValues),
                        'avg' => round(array_sum($numericValues) / count($numericValues), 2),
                        'sum' => array_sum($numericValues)
                    ];
                }
            }

            $columns[] = [
                'index' => $colIndex,
                'name' => $header,
                'data_type' => $dataType,
                'total_values' => $totalValues,
                'empty_count' => $emptyCount,
                'unique_count' => count($uniqueValues),
                'sample_values' => array_slice($values, 0, 5),
                'stats' => $stats,
            ];
        }

        return $columns;
    }

    /**
     * Obtiene una muestra de datos
     *
     * @param array $data
     * @param int $headerRow
     * @param int $limit
     * @return array
     */
    private function getDataSample(array $data, int $headerRow, int $limit): array
    {
        $sample = [];
        $headers = $data[$headerRow] ?? [];

        for ($i = $headerRow + 1; $i < count($data) && $i < $headerRow + $limit + 1; $i++) {
            if (!isset($data[$i])) {
                continue;
            }

            $row = [];
            foreach ($data[$i] as $colIndex => $value) {
                $columnName = $headers[$colIndex] ?? "Column_" . ($colIndex + 1);
                $row[$columnName] = $value;
            }

            $sample[] = $row;
        }

        return $sample;
    }

    /**
     * Extrae datos específicos basados en la selección del usuario
     *
     * @param string $filePath
     * @param array $selection Configuración de selección
     * @return array
     */
    public function extractData(string $filePath, array $selection): array
    {
        Log::info('DynamicExcelAnalyzer: Iniciando extracción de datos', [
            'file_path' => $filePath,
            'selection' => $selection
        ]);

        try {
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getSheet($selection['sheet_index'] ?? 0);
            
            $headerRow = $selection['header_row'];
            $selectedColumns = $selection['columns']; // Array de índices de columnas
            $labelColumn = $selection['label_column']; // Columna para etiquetas
            $valueColumns = $selection['value_columns']; // Columnas de valores

            $highestRow = $sheet->getHighestRow();
            $headers = [];
            $extractedData = [];

            // Leer encabezados
            foreach ($selectedColumns as $colIndex) {
                $coordinate = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1) . ($headerRow + 1);
                $cell = $sheet->getCell($coordinate);
                $headers[$colIndex] = $cell->getValue();
            }

            // Leer datos
            for ($row = $headerRow + 2; $row <= min($highestRow, $headerRow + 1000); $row++) {
                $rowData = [];
                $isEmpty = true;

                foreach ($selectedColumns as $colIndex) {
                    $coordinate = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1) . $row;
                    $cell = $sheet->getCell($coordinate);
                    $value = $cell->getValue();
                    
                    if (!empty($value)) {
                        $isEmpty = false;
                    }

                    $rowData[$headers[$colIndex]] = $value;
                }

                if (!$isEmpty) {
                    $extractedData[] = $rowData;
                }
            }

            // Procesar datos según el tipo de gráfica solicitado
            $chartData = $this->processDataForChart($extractedData, $labelColumn, $valueColumns);
            
            Log::info('DynamicExcelAnalyzer: Datos procesados para gráfica', [
                'total_rows' => count($extractedData),
                'labels_count' => count($chartData['labels']),
                'datasets_count' => count($chartData['datasets'])
            ]);

            return [
                'success' => true,
                'headers' => $headers,
                'data' => $extractedData,
                'chart_data' => $chartData,
                'total_rows' => count($extractedData)
            ];

        } catch (\Exception $e) {
            Log::error('DynamicExcelAnalyzer: Error al extraer datos', [
                'file_path' => $filePath,
                'selection' => $selection,
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'error' => 'Error al extraer datos: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Procesa datos para formato de gráfica
     *
     * @param array $data
     * @param int $labelColumnIndex
     * @param array $valueColumnsIndices
     * @return array
     */
    private function processDataForChart(array $data, int $labelColumnIndex, array $valueColumnsIndices): array
    {
        $labels = [];
        $datasets = [];

        // Obtener nombre de columnas
        $columnNames = array_keys($data[0] ?? []);
        $labelColumnName = $columnNames[$labelColumnIndex] ?? '';
        
        foreach ($data as $row) {
            $rowValues = array_values($row);
            $label = $rowValues[$labelColumnIndex] ?? '';
            $labels[] = $label;
        }

        foreach ($valueColumnsIndices as $valueColIndex) {
            $values = [];
            $columnName = $columnNames[$valueColIndex] ?? 'Serie ' . ($valueColIndex + 1);

            foreach ($data as $row) {
                $rowValues = array_values($row);
                $value = $rowValues[$valueColIndex] ?? 0;
                $values[] = is_numeric($value) ? floatval($value) : 0;
            }

            $datasets[] = [
                'label' => $columnName,
                'data' => $values
            ];
        }

        return [
            'labels' => $labels,
            'datasets' => $datasets
        ];
    }
}
