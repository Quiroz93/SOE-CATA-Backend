<?php

namespace App\Application\Statistics;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Exportar datos consolidados de fichas a Excel
 */
class ExportConsolidatedFichasExcel
{
    private Spreadsheet $spreadsheet;
    private int $currentRow = 1;

    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();
        $this->spreadsheet->getDefaultStyle()
            ->getFont()
            ->setName('Calibri')
            ->setSize(11);
    }

    /**
     * Generar archivo Excel con datos consolidados
     */
    public function generate(array $data): string
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $sheet->setTitle('Consolidado Fichas');

        // Título principal
        $this->addTitle($sheet);

        // Metadatos
        $this->addMetadata($sheet, $data['totales'] ?? []);

        // Tabla de estados consolidados
        $this->addConsolidatedStatesTable($sheet, $data['estados_globales'] ?? []);

        // Tabla detallada por ficha
        $this->addDetailedFichasTable($sheet, $data['fichas'] ?? []);

        // Ajustar ancho de columnas
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(50);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(15);

        // Generar archivo temporal
        $filename = now()->format('Y-m-d_H-i-s') . '_consolidado_fichas.xlsx';
        $filepath = storage_path("temp/{$filename}");

        if (!is_dir(storage_path('temp'))) {
            mkdir(storage_path('temp'), 0755, true);
        }

        $writer = new Xlsx($this->spreadsheet);
        $writer->save($filepath);

        return $filepath;
    }

    /**
     * Agregar título principal
     */
    private function addTitle($sheet): void
    {
        $sheet->setCellValue('A1', 'CONSOLIDADO DE REPORTES INDIVIDUALES POR FICHA');
        $sheet->mergeCells('A1:D1');

        $titleStyle = $sheet->getStyle('A1');
        $titleStyle->getFont()->setBold(true)->setSize(14)->setColor(new Color(Color::COLOR_WHITE));
        $titleStyle->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF39A900');
        $titleStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->getRowDimension(1)->setRowHeight(30);

        $this->currentRow = 3;
    }

    /**
     * Agregar metadatos
     */
    private function addMetadata($sheet, array $totales): void
    {
        $sheet->setCellValue('A' . $this->currentRow, 'Resumen de Consolidación');
        $sheet->mergeCells('A' . $this->currentRow . ':D' . $this->currentRow);
        $this->styleHeaderRow($sheet, $this->currentRow);
        $this->currentRow++;

        $labels = [
            'Total de Fichas:' => $totales['fichas'] ?? 0,
            'Total de Aprendices:' => $totales['aprendices'] ?? 0,
            'Estados Detectados:' => $totales['estados'] ?? 0,
            'Fecha de Generación:' => now()->format('Y-m-d H:i:s'),
        ];

        foreach ($labels as $label => $value) {
            $sheet->setCellValue('A' . $this->currentRow, $label);
            $sheet->setCellValue('B' . $this->currentRow, $value);

            $sheet->getStyle('A' . $this->currentRow)->getFont()->setBold(true);
            $this->currentRow++;
        }

        $this->currentRow += 2;
    }

    /**
     * Agregar tabla de estados consolidados
     */
    private function addConsolidatedStatesTable($sheet, array $estadosGlobales): void
    {
        $sheet->setCellValue('A' . $this->currentRow, 'Estados Consolidados');
        $sheet->mergeCells('A' . $this->currentRow . ':B' . $this->currentRow);
        $this->styleHeaderRow($sheet, $this->currentRow);
        $this->currentRow++;

        // Encabezados
        $headerRow = $this->currentRow;
        $sheet->setCellValue('A' . $headerRow, 'Estado');
        $sheet->setCellValue('B' . $headerRow, 'Total Aprendices');

        $this->styleTableHeader($sheet, $headerRow, 2);
        $this->currentRow++;

        // Datos ordenados
        $sorted = $estadosGlobales;
        arsort($sorted);

        $rowNum = 0;
        foreach ($sorted as $estado => $total) {
            $sheet->setCellValue('A' . $this->currentRow, $estado);
            $sheet->setCellValue('B' . $this->currentRow, (int) $total);

            $sheet->getStyle('B' . $this->currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            // Alternancia de colores
            if ($rowNum % 2 === 1) {
                $sheet->getStyle('A' . $this->currentRow . ':B' . $this->currentRow)
                    ->getFill()->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFF5F5F5');
            }

            $this->currentRow++;
            $rowNum++;
        }

        $this->currentRow += 2;
    }

    /**
     * Agregar tabla detallada por fichas
     */
    private function addDetailedFichasTable($sheet, array $fichas): void
    {
        $sheet->setCellValue('A' . $this->currentRow, 'Detalle de Aprendices por Ficha');
        $sheet->mergeCells('A' . $this->currentRow . ':D' . $this->currentRow);
        $this->styleHeaderRow($sheet, $this->currentRow);
        $this->currentRow++;

        // Encabezados
        $headerRow = $this->currentRow;
        $sheet->setCellValue('A' . $headerRow, 'Código Ficha');
        $sheet->setCellValue('B' . $headerRow, 'Identificación');
        $sheet->setCellValue('C' . $headerRow, 'Nombre');
        $sheet->setCellValue('D' . $headerRow, 'Estado');

        $this->styleTableHeader($sheet, $headerRow, 4);
        $this->currentRow++;

        // Datos de fichas
        foreach ($fichas as $codigoFicha => $fichaData) {
            $aprendices = $fichaData['aprendices'] ?? [];

            if (empty($aprendices)) {
                continue;
            }

            foreach ($aprendices as $idx => $aprendiz) {
                // Primera fila muestra código de ficha
                if ($idx === 0) {
                    $sheet->setCellValue('A' . $this->currentRow, $codigoFicha);
                    $sheet->getStyle('A' . $this->currentRow)->getFont()->setBold(true);
                } else {
                    $sheet->setCellValue('A' . $this->currentRow, '');
                }

                $sheet->setCellValue('B' . $this->currentRow, $aprendiz['identificacion'] ?? '');
                $sheet->setCellValue('C' . $this->currentRow, $aprendiz['nombre'] ?? '');
                $sheet->setCellValue('D' . $this->currentRow, $aprendiz['estado'] ?? '');

                // Aplicar borde siempre
                $this->styleBorderedCell($sheet, $this->currentRow, 4);

                // Alternancia de colores
                if ($idx % 2 === 1) {
                    $sheet->getStyle('A' . $this->currentRow . ':D' . $this->currentRow)
                        ->getFill()->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('FFF5F5F5');
                }

                $this->currentRow++;
            }

            // Línea separadora entre fichas
            $this->currentRow++;
        }
    }

    /**
     * Estilo para encabezado principal
     */
    private function styleHeaderRow($sheet, int $row): void
    {
        $style = $sheet->getStyle('A' . $row);
        $style->getFont()->setBold(true)->setSize(12)->setColor(new Color(Color::COLOR_WHITE));
        $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF39A900');
        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension($row)->setRowHeight(25);
    }

    /**
     * Estilo para encabezado de tabla
     */
    private function styleTableHeader($sheet, int $row, int $cols): void
    {
        for ($i = 1; $i <= $cols; $i++) {
            $cellAddress = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i) . $row;
            $style = $sheet->getStyle($cellAddress);

            $style->getFont()->setBold(true)->setColor(new Color(Color::COLOR_WHITE));
            $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF00304D');
            $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
            $style->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        }

        $sheet->getRowDimension($row)->setRowHeight(20);
    }

    /**
     * Aplicar bordes a celda
     */
    private function styleBorderedCell($sheet, int $row, int $cols): void
    {
        for ($i = 1; $i <= $cols; $i++) {
            $cellAddress = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i) . $row;
            $sheet->getStyle($cellAddress)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        }
    }
}
