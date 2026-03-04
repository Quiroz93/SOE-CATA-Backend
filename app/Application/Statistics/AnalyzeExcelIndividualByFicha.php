<?php

namespace App\Application\Statistics;

use App\Application\Statistics\Contracts\ExcelReportAnalyzer;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AnalyzeExcelIndividualByFicha implements ExcelReportAnalyzer
{
    /**
     * Mapeo de estados normalizados a formas canónicas
     * Esto asegura que todas las variaciones de un estado se mostren con una sola forma uniforme
     */
    private const CANONICAL_STATES = [
        'convocado matricula' => 'Convocado Matrícula',
        'convocado matrcula' => 'Convocado Matrícula',  // Sin í
        'convocado matr cula' => 'Convocado Matrícula',  // Con espacio en lugar de í
        'convocado matr icula' => 'Convocado Matrícula',  // Con espacio + i
        'anulado matricula' => 'Anulado Matrícula',
        'anulado matrcula' => 'Anulado Matrícula',  // Sin í
        'anulado matr cula' => 'Anulado Matrícula',  // Con espacio en lugar de í
        'anulado matr icula' => 'Anulado Matrícula',  // Con espacio + i
        'matriculado' => 'Matriculado',
        'inscrito' => 'Inscrito',
        'no admitido' => 'No Admitido',
        'cancelado' => 'Cancelado',
        'no seleccionado' => 'No Seleccionado',
        'seleccionado' => 'Seleccionado',
        'preinscrito' => 'Preinscrito',
        'pendiente' => 'Pendiente',
        'sin estado' => 'Sin estado',
    ];

    public function execute(string $filePath): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, false);

        if (count($rows) < 7) {
            throw new \Exception('El archivo no tiene la estructura esperada para reporte individual por ficha.');
        }

        // Normalizar todas las filas a UTF-8 para evitar problemas de codificación
        $rows = array_map(fn($row) => $this->ensureUtf8($row), $rows);

        [$ficha, $programa] = $this->extractHeaderData($rows);
        $headerRowIndex = $this->findTableHeaderRow($rows);

        if ($headerRowIndex === null) {
            throw new \Exception('No se encontró la fila de encabezados de tabla (Identificación, Nombre, Estado).');
        }

        $headers = array_map(fn($value) => $this->normalize((string) $value), $rows[$headerRowIndex]);
        $identificacionIndex = $this->findColumn($headers, ['identificacion', 'documento', 'id']);
        $nombreIndex = $this->findColumn($headers, ['nombre', 'nombres']);
        $estadoIndex = $this->findColumn($headers, ['estado']);

        if ($identificacionIndex === null || $nombreIndex === null || $estadoIndex === null) {
            throw new \Exception('No se detectaron correctamente las columnas Identificación, Nombre y Estado.');
        }

        $tabla = [];
        $estadoCounts = [];

        for ($i = $headerRowIndex + 1; $i < count($rows); $i++) {
            $row = $rows[$i];

            $identificacion = trim((string) ($row[$identificacionIndex] ?? ''));
            $nombre = trim((string) ($row[$nombreIndex] ?? ''));
            $estado = trim((string) ($row[$estadoIndex] ?? ''));

            if ($identificacion === '' && $nombre === '' && $estado === '') {
                continue;
            }

            if ($estado === '') {
                $estado = 'Sin estado';
            }

            $tabla[] = [
                'identificacion' => $identificacion,
                'nombre' => $nombre,
                'estado' => $estado,
            ];

            // Normalizar el estado para agrupar variaciones
            $estadoNormalizado = $this->normalizeEstado($estado);
            $estadoCounts[$estadoNormalizado] = ($estadoCounts[$estadoNormalizado] ?? 0) + 1;
        }

        if (count($tabla) === 0) {
            throw new \Exception('No se encontraron registros de aprendices en el archivo.');
        }

        arsort($estadoCounts);
        
        // Reemplazar claves normalizadas con estados canónicos en el diccionario de conteos
        $estadoTotales = [];
        foreach ($estadoCounts as $estadoNorm => $count) {
            $estadoCanonica = $this->getCanonicalEstado($estadoNorm);
            $estadoTotales[$estadoCanonica] = $count;
        }
        arsort($estadoTotales);

        return [
            'report_kind' => 'individual_ficha',
            'labels' => array_keys($estadoTotales),
            'series' => array_values($estadoTotales),
            'tabla' => $tabla,
            'estado_totales' => $estadoTotales,
            'metadata' => [
                'ficha' => $ficha,
                'programa' => $programa,
                'totalAprendices' => count($tabla),
                'totalEstados' => count($estadoTotales),
            ],
        ];
    }

    private function extractHeaderData(array $rows): array
    {
        $ficha = '';
        $programa = '';

        for ($i = 0; $i < min(6, count($rows)); $i++) {
            $label = $this->normalize((string) ($rows[$i][0] ?? ''));
            $value = trim((string) ($rows[$i][1] ?? ''));

            if ($label === 'codigoficha') {
                $ficha = $value;
            }

            if ($label === 'programadeformacion') {
                $programa = $value;
            }
        }

        return [$ficha, $programa];
    }

    private function findTableHeaderRow(array $rows): ?int
    {
        for ($i = 0; $i < count($rows); $i++) {
            $headers = array_map(fn($value) => $this->normalize((string) $value), $rows[$i]);

            $hasId = $this->findColumn($headers, ['identificacion']) !== null;
            $hasNombre = $this->findColumn($headers, ['nombre']) !== null;
            $hasEstado = $this->findColumn($headers, ['estado']) !== null;

            if ($hasId && $hasNombre && $hasEstado) {
                return $i;
            }
        }

        return null;
    }

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

    private function normalize(string $value): string
    {
        $value = mb_strtolower(trim($value));
        $value = str_replace(
            ['á', 'é', 'í', 'ó', 'ú', 'ü', 'ñ'],
            ['a', 'e', 'i', 'o', 'u', 'u', 'n'],
            $value
        );
        return str_replace([' ', '_', '-', '.', '/', ':'], '', $value);
    }

    /**
     * Normaliza un estado para agrupar variaciones (espacios, mayúsculas, etc.)
     * Mantiene legibilidad a diferencia de normalize() que es más agresiva
     */
    private function normalizeEstado(string $estado): string
    {
        // PASO 1: Asegurar UTF-8 válido
        if (!mb_check_encoding($estado, 'UTF-8')) {
            $estado = mb_convert_encoding($estado, 'UTF-8', mb_detect_encoding($estado));
        }
        
        // PASO 2: Decodificar entidades HTML (ej: &iacute; -> í)
        $estado = html_entity_decode($estado, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        // PASO 3: Remover caracteres de control y bytes inválidos
        $estado = preg_replace('/[\x00-\x1F\x7F-\x9F]/u', '', $estado);
        
        // PASO 3.5: Limpiar patrones comunes de mojibake (comillas + vocal)
        $estado = preg_replace("/['`´]([aeiouAEIOU])/u", '$1', $estado);
        
        // PASO 3.6: Reemplazar cualquier secuencia de caracteres no deseados
        // Mantener solo letras, números, espacios, guiones y underscores
        $estado = preg_replace('/[^\p{L}\p{N}\s_-]/u', ' ', $estado);
        
        // PASO 4: Convertir a minúsculas usando mb_strtolower
        $estado = mb_strtolower(trim($estado), 'UTF-8');
        
        // PASO 5: Normalizar acentos usando iconv para mejor cobertura
        $estado = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $estado);
        if ($estado === false) {
            // Fallback manual si iconv falla
            $estado = str_replace(
                ['á', 'é', 'í', 'ó', 'ú', 'ü', 'ñ', 'à', 'è', 'ì', 'ò', 'ù'],
                ['a', 'e', 'i', 'o', 'u', 'u', 'n', 'a', 'e', 'i', 'o', 'u'],
                $estado
            );
        }
        
        // PASO 6: Normalizar espacios múltiples y guiones/underscore a espacio único
        $estado = preg_replace('/[\s_-]+/', ' ', $estado);
        
        // PASO 7: Limpiar espacios al inicio y final
        return trim($estado);
    }

    /**
     * Obtiene la forma canónica de un estado normalizado
     * Usa el mapeo CANONICAL_STATES para devolver una forma estándar
     */
    private function getCanonicalEstado(string $estadoNormalizado): string
    {
        // Si existe en el mapeo canónico, usar ese
        if (isset(self::CANONICAL_STATES[$estadoNormalizado])) {
            return self::CANONICAL_STATES[$estadoNormalizado];
        }
        
        // Fallback: buscar patrones conocidos para manejar variaciones no mapeadas
        // Esto captura casos donde los caracteres están tan corruptos que no coinciden exactamente
        
        // Patrón: "convocado" + "matr" + algo + "cula"
        if (preg_match('/^convocado\s+matr.*cula$/i', $estadoNormalizado)) {
            return 'Convocado Matrícula';
        }
        
        // Patrón: "anulado" + "matr" + algo + "cula"
        if (preg_match('/^anulado\s+matr.*cula$/i', $estadoNormalizado)) {
            return 'Anulado Matrícula';
        }
        
        // Si no, devolver el estado normalizado con primera letra mayúscula de cada palabra
        return ucwords($estadoNormalizado);
    }

    /**
     * Garantiza que todos los valores en una fila están correctamente codificados en UTF-8
     * Esto evita problemas de mojibake cuando el Excel tiene caracteres especiales
     */
    private function ensureUtf8(array $row): array
    {
        return array_map(function ($value) {
            if (!is_string($value)) {
                return $value;
            }

            // Si el valor ya es UTF-8 válido, devolverlo tal cual
            if (mb_check_encoding($value, 'UTF-8')) {
                return $value;
            }

            // Si no es UTF-8, intentar detectar y convertir la codificación
            $encoding = mb_detect_encoding($value, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true);
            if ($encoding && $encoding !== 'UTF-8') {
                return mb_convert_encoding($value, 'UTF-8', $encoding);
            }

            // Último recurso: asumir Windows-1252 y convertir
            return mb_convert_encoding($value, 'UTF-8', 'Windows-1252');
        }, $row);
    }
}
