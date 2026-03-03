<?php

namespace App\Application\Statistics;

use App\Application\Statistics\Contracts\ExcelReportAnalyzer;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Servicio de aplicación para analizar archivos Excel
 * y generar estadísticas agrupadas por COD_FICHA
 */
class AnalyzeExcelByProgram implements ExcelReportAnalyzer
{
    /**
     * Analizar archivo Excel y devolver estadísticas por COD_FICHA
     * Agrupa por ficha (referencia) pero muestra nombre del programa
     *
     * @param string $filePath Ruta completa al archivo Excel
     * @return array ['totalRegistros', 'labels', 'series', 'tabla', 'metadata']
     * @throws \Exception Si el archivo no es válido o no tiene datos
     */
    public function execute(string $filePath): array
    {
        // Cargar archivo Excel usando PhpSpreadsheet
        $spreadsheet = IOFactory::load($filePath);
        $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, false);

        if (count($rows) < 2) {
            throw new \Exception('El archivo no tiene datos suficientes para analizar.');
        }

        // Encabezados en fila 8 (índice 7) según especificación,
        // con fallback automático buscando en primeras filas.
        $headerRowIndex = 7;
        $maxHeaderScanRows = 20;
        $headers = isset($rows[$headerRowIndex])
            ? array_map(fn($h) => $this->normalize((string) $h), $rows[$headerRowIndex])
            : [];

        $columnas = $this->buildColumnsMap($headers);

        if ($columnas['ficha'] === null || $columnas['programa'] === null) {
            for ($i = 0; $i < min(count($rows), $maxHeaderScanRows); $i++) {
                $candidateHeaders = array_map(fn($h) => $this->normalize((string) $h), $rows[$i]);
                $candidateColumns = $this->buildColumnsMap($candidateHeaders);

                if ($candidateColumns['ficha'] !== null && $candidateColumns['programa'] !== null) {
                    $headerRowIndex = $i;
                    $headers = $candidateHeaders;
                    $columnas = $candidateColumns;
                    break;
                }
            }
        }

        if ($columnas['ficha'] === null || $columnas['programa'] === null) {
            throw new \Exception('No se encontraron las columnas requeridas. Verifique que incluya: "COD_FICHA" (referencia) y "DENOMINACION_PROGRAMA" (nombre del programa).');
        }

        // Inicializar contadores y estructuras
        // Agrupados por COD_FICHA (clave principal)
        $fichasPorPrograma = [];      // ['COD_FICHA' => conteo de registros]
        $nombresPorFicha = [];         // ['COD_FICHA' => 'DENOMINACION_PROGRAMA']
        $estadoPorFicha = [];          // ['COD_FICHA' => ['estado' => conteo]]
        $centrosPorFicha = [];         // ['COD_FICHA' => ['centro' => conteo]]
        $regionalPorFicha = [];        // ['COD_FICHA' => ['region' => conteo]]
        $inscritosPrimeraPorFicha = []; // ['COD_FICHA' => total primera opción]
        $inscritosSegundaPorFicha = []; // ['COD_FICHA' => total segunda opción]
        $inscritosPorFicha = [];        // ['COD_FICHA' => total inscriptos]
        $cuposPorFicha = [];           // ['COD_FICHA' => total cupos]
        $nivelPorFicha = [];           // ['COD_FICHA' => nivel_formacion]
        $totalRegistros = 0;
        $totalInscritos = 0;
        $totalCupos = 0;

        // Procesar filas (saltando el encabezado detectado)
        for ($i = $headerRowIndex + 1; $i < count($rows); $i++) {
            $row = $rows[$i];

            // Extraer COD_FICHA (clave de agrupación)
            $ficha = trim((string)($row[$columnas['ficha']] ?? ''));
            if ($ficha === '') {
                continue;
            }

            // Extraer DENOMINACION_PROGRAMA (etiqueta visual)
            $programa = trim((string)($row[$columnas['programa']] ?? 'Sin programa'));
            if ($programa === '') {
                $programa = 'Sin programa';
            }

            // Extraer otros campos
            $estado = $columnas['estado'] !== null 
                ? trim((string)($row[$columnas['estado']] ?? 'Sin estado'))
                : 'Sin estado';
            if ($estado === '') $estado = 'Sin estado';

            $centro = $columnas['centro'] !== null 
                ? trim((string)($row[$columnas['centro']] ?? ''))
                : '';

            $regional = $columnas['regional'] !== null 
                ? trim((string)($row[$columnas['regional']] ?? ''))
                : '';

            $nivel = $columnas['nivel'] !== null 
                ? trim((string)($row[$columnas['nivel']] ?? ''))
                : '';

            // Extraer valores numéricos
            $cupo = $columnas['cupo'] !== null 
                ? (int)($row[$columnas['cupo']] ?? 0)
                : 0;

            $inscritos1 = $columnas['inscritos1'] !== null 
                ? (int)($row[$columnas['inscritos1']] ?? 0)
                : 0;

            $inscritos2 = $columnas['inscritos2'] !== null 
                ? (int)($row[$columnas['inscritos2']] ?? 0)
                : 0;

            $totalInscritosRow = $inscritos1 + $inscritos2;

            // Contabilizar por COD_FICHA (no por nombre de programa)
            $fichasPorPrograma[$ficha] = ($fichasPorPrograma[$ficha] ?? 0) + 1;
            $nombresPorFicha[$ficha] = $programa;  // Guardar nombre del programa por ficha
            $estadoPorFicha[$ficha][$estado] = ($estadoPorFicha[$ficha][$estado] ?? 0) + 1;
            
            if ($centro !== '') {
                $centrosPorFicha[$ficha][$centro] = ($centrosPorFicha[$ficha][$centro] ?? 0) + 1;
            }

            if ($regional !== '') {
                $regionalPorFicha[$ficha][$regional] = ($regionalPorFicha[$ficha][$regional] ?? 0) + 1;
            }

            if ($nivel !== '') {
                $nivelPorFicha[$ficha] = $nivel;
            }

            $inscritosPrimeraPorFicha[$ficha] = ($inscritosPrimeraPorFicha[$ficha] ?? 0) + $inscritos1;
            $inscritosSegundaPorFicha[$ficha] = ($inscritosSegundaPorFicha[$ficha] ?? 0) + $inscritos2;
            $inscritosPorFicha[$ficha] = ($inscritosPorFicha[$ficha] ?? 0) + $totalInscritosRow;
            $cuposPorFicha[$ficha] = ($cuposPorFicha[$ficha] ?? 0) + $cupo;

            $totalRegistros++;
            $totalInscritos += $totalInscritosRow;
            $totalCupos += $cupo;
        }

        // Ordenar fichas por cantidad de registros (mayor a menor)
        arsort($fichasPorPrograma);

        // Construir tabla de resultados enriquecida
        $tabla = [];
        foreach ($fichasPorPrograma as $ficha => $total) {
            $programa = $nombresPorFicha[$ficha] ?? 'Sin programa';
            $inscritosPrimera = $inscritosPrimeraPorFicha[$ficha] ?? 0;
            $inscritosSegunda = $inscritosSegundaPorFicha[$ficha] ?? 0;
            $inscritos = $inscritosPorFicha[$ficha] ?? 0;
            $cuposTotal = $cuposPorFicha[$ficha] ?? 0;
            $demandaPorcentaje = $cuposTotal > 0 ? round(($inscritosPrimera / $cuposTotal) * 100, 2) : 0;
            $sobrecupoPrimera = max($inscritosPrimera - $cuposTotal, 0);
            $ocupacion = $cuposTotal > 0 ? round(($inscritos / $cuposTotal) * 100, 2) : 0;

            // Centros únicos para esta ficha
            $centrosUnicos = isset($centrosPorFicha[$ficha]) 
                ? array_keys($centrosPorFicha[$ficha]) 
                : [];

            // Principales centros
            $centrosTop = [];
            if (isset($centrosPorFicha[$ficha])) {
                $centrosCopia = $centrosPorFicha[$ficha];
                arsort($centrosCopia);
                $centrosTop = array_slice(array_keys($centrosCopia), 0, 3);
            }

            $tabla[] = [
                'ficha' => $ficha,
                'programa' => $programa,
                'total' => $total,
                'porcentaje' => count($fichasPorPrograma) > 0 ? round(($total / count($fichasPorPrograma)) * 100, 2) : 0,
                'inscritos_primera' => $inscritosPrimera,
                'inscritos_segunda' => $inscritosSegunda,
                'inscritos' => $inscritos,
                'cupos' => $cuposTotal,
                'demanda_porcentaje' => $demandaPorcentaje,
                'sobrecupo_primera' => $sobrecupoPrimera,
                'ocupacion' => $ocupacion,
                'nivel' => $nivelPorFicha[$ficha] ?? '',
                'centros_count' => count($centrosUnicos),
                'centros_top' => $centrosTop,
                'estados' => $estadoPorFicha[$ficha] ?? [],
            ];
        }

        // Metadata adicional
        $metadata = [
            'totalFichas' => count($fichasPorPrograma),
            'totalRegistros' => $totalRegistros,
            'totalInscritos' => $totalInscritos,
            'totalCupos' => $totalCupos,
            'ocupacionPromedio' => $totalCupos > 0 ? round(($totalInscritos / $totalCupos) * 100, 2) : 0,
            'columnasDetectadas' => array_filter($columnas, fn($v) => $v !== null),
        ];

        return [
            'totalRegistros' => $totalRegistros,
            'labels' => array_map(fn($f) => $nombresPorFicha[$f] ?? 'Sin programa', array_keys($fichasPorPrograma)),
            'series' => array_values($fichasPorPrograma),
            'tabla' => $tabla,
            'metadata' => $metadata,
        ];
    }

    /**
     * Normalizar cadena para búsqueda flexible
     */
    private function normalize(string $value): string
    {
        $value = mb_strtolower(trim($value));
        $value = str_replace([' ', '_', '-', '.', '/', ':'], '', $value);
        return $value;
    }

    /**
     * Encontrar índice de columna por múltiples candidatos
     */
    private function findColumn(array $headers, array $candidates): ?int
    {
        foreach ($headers as $index => $header) {
            foreach ($candidates as $candidate) {
                if (str_contains($header, $this->normalize($candidate))) {
                    return $index;
                }
            }
        }
        return null;
    }

    /**
     * Construir mapeo de columnas detectadas.
     * Prioriza columnas específicas SENA (ej: DENOMINACION_PROGRAMA sobre COD_PROGRAMA)
     */
    private function buildColumnsMap(array $headers): array
    {
        return [
            // Para programa, busca primero DENOMINACION (más específico) antes que COD
            'programa' => $this->findColumnExact($headers, 'denominacionprograma') 
                ?? $this->findColumn($headers, ['programa', 'programadeformacion', 'nombreprograma', 'programaformacion']),
            
            'estado' => $this->findColumn($headers, [
                'estadoficha', 'estado', 'estadoinscripcion',
                'situacion', 'estadopreinscrito'
            ]),
            'regional' => $this->findColumn($headers, ['regional', 'nombreregional']),
            'municipio' => $this->findColumn($headers, ['municipio', 'ciudad']),
            'centro' => $this->findColumnExact($headers, 'centroformacion') 
                ?? $this->findColumn($headers, ['centro', 'nombrecentro']),
            'ficha' => $this->findColumnExact($headers, 'codficha') 
                ?? $this->findColumn($headers, ['ficha', 'numeroficha']),
            'jornada' => $this->findColumn($headers, ['jornada', 'turno']),
            'nivel' => $this->findColumn($headers, ['nivelformacion', 'nivel', 'tipoformacion']),
            'cupo' => $this->findColumn($headers, ['cupo', 'cupos', 'capacidad']),
            'inscritos1' => $this->findColumn($headers, [
                'inscritosprimeraopcion', 'inscritosprimeraopc', 'primeraopcion'
            ]),
            'inscritos2' => $this->findColumn($headers, [
                'inscritossegundaopcion', 'inscritossegundaopc', 'segundaopcion'
            ]),
        ];
    }

    /**
     * Buscar columna por coincidencia exacta (después de normalización)
     */
    private function findColumnExact(array $headers, string $candidate): ?int
    {
        $normalized = $this->normalize($candidate);
        foreach ($headers as $index => $header) {
            if ($header === $normalized) {
                return $index;
            }
        }
        return null;
    }
}
