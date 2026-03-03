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
     * Procesar archivo Excel subido y retornar estadísticas
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function upload(Request $request): JsonResponse
    {
        // Validación
        $request->validate([
            'file' => 'required|file|mimes:xls,xlsx|max:10240', // 10MB máximo
            'report_kind' => 'required|in:general_inscripciones,individual_ficha',
        ], [
            'file.required' => 'Debe seleccionar un archivo Excel.',
            'file.mimes' => 'El archivo debe ser formato Excel (.xls o .xlsx).',
            'file.max' => 'El archivo no puede pesar más de 10MB.',
            'report_kind.required' => 'Debe seleccionar un tipo de reporte.',
            'report_kind.in' => 'El tipo de reporte seleccionado no es válido.',
        ]);

        try {
            $file = $request->file('file');
            $reportKind = (string) $request->input('report_kind');

            // Resolver analizador según tipo de reporte
            $factory = new DashboardReportAnalyzerFactory();
            $analyzer = $factory->make($reportKind);
            $result = $analyzer->execute($file->getRealPath());

            if (!isset($result['report_kind'])) {
                $result['report_kind'] = $reportKind;
            }

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al procesar el archivo: ' . $e->getMessage()
            ], 422);
        }
    }
}
