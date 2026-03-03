<?php

namespace App\Http\Controllers\Admin;

use App\Application\Statistics\DashboardReportAnalyzerFactory;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Controlador para estadísticas en tiempo real del dashboard
 */
class DashboardStatsController extends Controller
{
    /**
     * Procesar archivo(s) Excel subido(s) y retornar estadísticas
     * Soporta un único archivo o múltiples archivos para consolidación
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function upload(Request $request): JsonResponse
    {
        // Validación: soportar 'file' (único) o 'files' (múltiples)
        $request->validate([
            'file' => 'nullable|file|mimes:xls,xlsx|max:10240',
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:xls,xlsx|max:10240',
            'report_kind' => 'required|in:general_inscripciones,individual_ficha,individual_ficha_consolidado',
        ], [
            'file.mimes' => 'El archivo debe ser formato Excel (.xls o .xlsx).',
            'file.max' => 'El archivo no puede pesar más de 10MB.',
            'files.*.mimes' => 'Todos los archivos deben ser formato Excel (.xls o .xlsx).',
            'files.*.max' => 'Cada archivo no puede pesar más de 10MB.',
            'report_kind.required' => 'Debe seleccionar un tipo de reporte.',
            'report_kind.in' => 'El tipo de reporte seleccionado no es válido.',
        ]);

        try {
            $reportKind = (string) $request->input('report_kind');

            // Determinar si es consolidación (múltiples archivos)
            if ($reportKind === 'individual_ficha_consolidado' && $request->hasFile('files')) {
                return $this->processConsolidatedFichas($request, $reportKind);
            }

            // Procesar un único archivo
            if ($request->hasFile('file')) {
                return $this->processSingleFile($request, $reportKind);
            }

            return response()->json([
                'message' => 'Debe proporcionar al menos un archivo Excel.'
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al procesar el archivo: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Procesar un único archivo
     */
    private function processSingleFile(Request $request, string $reportKind): JsonResponse
    {
        $file = $request->file('file');
        $factory = new DashboardReportAnalyzerFactory();
        $analyzer = $factory->make($reportKind);
        $result = $analyzer->execute($file->getRealPath());

        if (!isset($result['report_kind'])) {
            $result['report_kind'] = $reportKind;
        }

        return response()->json($result);
    }

    /**
     * Procesar múltiples archivos para consolidación
     */
    private function processConsolidatedFichas(Request $request, string $reportKind): JsonResponse
    {
        $files = $request->file('files') ?? [];

        if (empty($files)) {
            return response()->json([
                'message' => 'Debe cargar al menos un archivo para consolidar.'
            ], 422);
        }

        $filePaths = array_map(fn($file) => $file->getRealPath(), $files);
        $consolidator = new \App\Application\Statistics\ConsolidateIndividualFichas();
        $result = $consolidator->execute($filePaths);

        return response()->json($result);
    }
}
