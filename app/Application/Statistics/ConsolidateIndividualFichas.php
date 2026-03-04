<?php

namespace App\Application\Statistics;

use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Consolidador de reportes individuales por ficha
 * Procesa múltiples archivos Excel y consolida los datos por ficha y aprendiz
 */
class ConsolidateIndividualFichas
{
    private AnalyzeExcelIndividualByFicha $analyzer;

    public function __construct()
    {
        $this->analyzer = new AnalyzeExcelIndividualByFicha();
    }

    /**
     * Procesar múltiples archivos y consolidarlos
     *
     * @param array $filePaths Array de rutas de archivo
     * @return array Datos consolidados
     */
    public function execute(array $filePaths): array
    {
        if (empty($filePaths)) {
            throw new \Exception('No se proporcionaron archivos para procesar.');
        }

        $fichasData = [];
        $estadoCounts = [];
        $totalAprendices = 0;

        // Procesar cada archivo
        foreach ($filePaths as $filePath) {
            if (!file_exists($filePath)) {
                continue; // Saltar archivos no encontrados
            }

            try {
                $result = $this->analyzer->execute($filePath);

                // Extraer datos de la ficha
                $ficha = $result['metadata']['ficha'] ?? 'Sin identificar';
                $programa = $result['metadata']['programa'] ?? 'Sin identificar';

                // Inicializar estructura de ficha si no existe
                if (!isset($fichasData[$ficha])) {
                    $fichasData[$ficha] = [
                        'ficha' => $ficha,
                        'programa' => $programa,
                        'aprendices' => [],
                        'estadoCounts' => [],
                        'totalAprendices' => 0,
                    ];
                }

                // Agregar aprendices de este archivo
                foreach ($result['tabla'] as $aprendiz) {
                    $fichasData[$ficha]['aprendices'][] = $aprendiz;
                    $estado = $aprendiz['estado'];

                    // Contar estados por ficha
                    $fichasData[$ficha]['estadoCounts'][$estado] = 
                        ($fichasData[$ficha]['estadoCounts'][$estado] ?? 0) + 1;

                    // Contar estados globales
                    $estadoCounts[$estado] = ($estadoCounts[$estado] ?? 0) + 1;

                    $fichasData[$ficha]['totalAprendices']++;
                    $totalAprendices++;
                }
            } catch (\Exception $e) {
                // Registrar error pero continuar procesando otros archivos
                error_log('Error procesando archivo ' . basename($filePath) . ': ' . $e->getMessage());
            }
        }

        if (empty($fichasData)) {
            throw new \Exception('No se pudieron procesar correctamente los archivos.');
        }

        // Ordenar estados por cantidad descendente
        arsort($estadoCounts);

        // Ordernar fichas por código
        ksort($fichasData);

        return [
            'report_kind' => 'individual_ficha_consolidado',
            'consolidado' => true,
            'totales' => [
                'fichas' => count($fichasData),
                'aprendices' => $totalAprendices,
                'estados' => count($estadoCounts),
            ],
            'fichas' => $fichasData,
            'estados_globales' => $estadoCounts,
            'labels' => array_keys($estadoCounts),
            'series' => array_values($estadoCounts),
            'timestamp' => now()->toIso8601String(),
        ];
    }
}
