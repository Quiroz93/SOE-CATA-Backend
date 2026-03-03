<?php

namespace App\Application\Statistics;

use App\Application\Statistics\Contracts\ExcelReportAnalyzer;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AnalyzeExcelIndividualByFicha implements ExcelReportAnalyzer
{
    public function execute(string $filePath): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, false);

        if (count($rows) < 7) {
            throw new \Exception('El archivo no tiene la estructura esperada para reporte individual por ficha.');
        }

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

            $estadoCounts[$estado] = ($estadoCounts[$estado] ?? 0) + 1;
        }

        if (count($tabla) === 0) {
            throw new \Exception('No se encontraron registros de aprendices en el archivo.');
        }

        arsort($estadoCounts);

        return [
            'report_kind' => 'individual_ficha',
            'labels' => array_keys($estadoCounts),
            'series' => array_values($estadoCounts),
            'tabla' => $tabla,
            'estado_totales' => $estadoCounts,
            'metadata' => [
                'ficha' => $ficha,
                'programa' => $programa,
                'totalAprendices' => count($tabla),
                'totalEstados' => count($estadoCounts),
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
}
