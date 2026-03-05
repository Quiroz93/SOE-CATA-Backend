<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DynamicExcelAnalyzerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class DynamicChartController extends Controller
{
    protected $analyzerService;

    public function __construct(DynamicExcelAnalyzerService $analyzerService)
    {
        $this->analyzerService = $analyzerService;
    }

    /**
     * Analiza un archivo Excel cargado y devuelve su estructura
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function analyzeFile(Request $request)
    {
        Log::info('DynamicChart: Solicitud de análisis de archivo recibida', [
            'ip' => $request->ip(),
            'user_id' => Auth::id(),
            'has_file' => $request->hasFile('file'),
            'file_name' => $request->hasFile('file') ? $request->file('file')->getClientOriginalName() : null
        ]);

        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|file|mimes:xlsx,xls|max:10240', // Max 10MB
            ]);

            if ($validator->fails()) {
                Log::warning('DynamicChart: Validación de archivo fallida', [
                    'errors' => $validator->errors()->toArray()
                ]);

                return response()->json([
                    'success' => false,
                    'error' => 'Archivo inválido. Por favor, carga un archivo Excel válido (.xlsx o .xls) menor a 10MB.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $file = $request->file('file');
            
            // Guardar archivo temporalmente
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = storage_path('app/temp/' . $fileName);
            
            Log::info('DynamicChart: Guardando archivo temporal', [
                'file_name' => $fileName,
                'file_path' => $filePath,
                'file_size' => $file->getSize()
            ]);
            
            // Crear directorio si no existe
            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }
            
            $file->move(storage_path('app/temp'), $fileName);

            // Analizar archivo
            $analysis = $this->analyzerService->analyzeExcelFile($filePath);

            Log::info('DynamicChart: Análisis completado', [
                'success' => $analysis['success'],
                'file_name' => $fileName
            ]);

            if (!$analysis['success']) {
                // Limpiar archivo temporal
                if (file_exists($filePath)) {
                    unlink($filePath);
                    Log::info('DynamicChart: Archivo temporal eliminado por error', [
                        'file_path' => $filePath
                    ]);
                }

                return response()->json([
                    'success' => false,
                    'error' => $analysis['error']
                ], 400);
            }

            // Guardar referencia del archivo en sesión
            session(['dynamic_chart_file' => $filePath]);

            Log::info('DynamicChart: Análisis exitoso, respuesta enviada', [
                'file_name' => $fileName,
                'total_sheets' => $analysis['total_sheets'] ?? 0
            ]);

            return response()->json([
                'success' => true,
                'analysis' => $analysis,
                'file_path' => $fileName
            ]);

        } catch (\Exception $e) {
            Log::error('DynamicChart: Error en analyzeFile', [
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Error al procesar el archivo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Extrae datos específicos basados en la selección del usuario
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function extractData(Request $request)
    {
        Log::info('DynamicChart: Solicitud de extracción de datos recibida', [
            'ip' => $request->ip(),
            'user_id' => Auth::id(),
            'sheet_index' => $request->input('sheet_index'),
            'columns_count' => count($request->input('columns', [])),
            'value_columns_count' => count($request->input('value_columns', []))
        ]);

        try {
            $validator = Validator::make($request->all(), [
                'file_path' => 'required|string',
                'sheet_index' => 'required|integer|min:0',
                'header_row' => 'required|integer|min:0',
                'columns' => 'required|array|min:1',
                'label_column' => 'required|integer|min:0',
                'value_columns' => 'required|array|min:1',
                'chart_config' => 'required|array',
            ]);

            if ($validator->fails()) {
                Log::warning('DynamicChart: Validación de extracción fallida', [
                    'errors' => $validator->errors()->toArray()
                ]);

                return response()->json([
                    'success' => false,
                    'error' => 'Datos de selección inválidos.',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Obtener ruta del archivo
            $fileName = $request->input('file_path');
            $filePath = storage_path('app/temp/' . $fileName);

            Log::info('DynamicChart: Intentando extraer datos de archivo', [
                'file_name' => $fileName,
                'file_exists' => file_exists($filePath)
            ]);

            if (!file_exists($filePath)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Archivo no encontrado. Por favor, vuelve a cargar el archivo.'
                ], 404);
            }

            $selection = [
                'sheet_index' => $request->input('sheet_index'),
                'header_row' => $request->input('header_row'),
                'columns' => $request->input('columns'),
                'label_column' => $request->input('label_column'),
                'value_columns' => $request->input('value_columns'),
            ];

            // Extraer datos
            $result = $this->analyzerService->extractData($filePath, $selection);

            Log::info('DynamicChart: Datos extraídos', [
                'success' => $result['success'],
                'total_rows' => $result['total_rows'] ?? 0
            ]);

            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'error' => $result['error']
                ], 400);
            }

            // Agregar configuración de gráfica
            $result['chart_config'] = $request->input('chart_config');

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('DynamicChart: Error en extractData', [
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Error al extraer datos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Limpia archivos temporales
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cleanupTempFiles(Request $request)
    {
        Log::info('DynamicChart: Solicitud de limpieza de archivos temporales', [
            'ip' => $request->ip(),
            'user_id' => Auth::id(),
            'file_path' => $request->input('file_path')
        ]);

        try {
            $fileName = $request->input('file_path');
            
            if ($fileName) {
                $filePath = storage_path('app/temp/' . $fileName);
                
                if (file_exists($filePath)) {
                    unlink($filePath);
                    Log::info('DynamicChart: Archivo temporal eliminado', [
                        'file_path' => $filePath
                    ]);
                }
            }

            // Limpiar archivos temporales antiguos (más de 1 hora)
            $tempDir = storage_path('app/temp');
            if (is_dir($tempDir)) {
                $files = glob($tempDir . '/*');
                $now = time();
                $deletedCount = 0;

                foreach ($files as $file) {
                    if (is_file($file)) {
                        if ($now - filemtime($file) >= 3600) { // 1 hora
                            unlink($file);
                            $deletedCount++;
                        }
                    }
                }

                Log::info('DynamicChart: Archivos antiguos eliminados', [
                    'deleted_count' => $deletedCount
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Archivos temporales limpiados correctamente.'
            ]);

        } catch (\Exception $e) {
            Log::error('DynamicChart: Error en cleanupTempFiles', [
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Error al limpiar archivos temporales: ' . $e->getMessage()
            ], 500);
        }
    }
}
