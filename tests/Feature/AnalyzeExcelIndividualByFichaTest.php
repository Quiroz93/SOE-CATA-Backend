<?php

namespace Tests\Feature;

use App\Application\Statistics\AnalyzeExcelIndividualByFicha;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

class AnalyzeExcelIndividualByFichaTest extends TestCase
{
    protected string $testFilePath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->testFilePath = storage_path('app/test_analysis_individual.xlsx');
        $this->createTestExcelFile();
    }

    protected function tearDown(): void
    {
        if (file_exists($this->testFilePath)) {
            unlink($this->testFilePath);
        }

        parent::tearDown();
    }

    private function createTestExcelFile(): void
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'REPORTE INDIVIDUAL POR FICHA');
        $sheet->setCellValue('A2', 'CENTRO DE FORMACION');
        $sheet->setCellValue('A3', 'Código Ficha');
        $sheet->setCellValue('B3', '3410558');
        $sheet->setCellValue('A4', 'Programa de Formación');
        $sheet->setCellValue('B4', 'GESTION CONTABLE Y DE INFORMACION FINANCIERA');

        $sheet->setCellValue('A6', 'Identificación');
        $sheet->setCellValue('B6', 'Nombre');
        $sheet->setCellValue('C6', 'Estado');

        $data = [
            ['CC - 1096950213', 'ANGEL HERNANDEZ URIBE', 'Matriculado'],
            ['CC - 1007917194', 'ANGIE DANIELA JAIMES SANDOVAL', 'No Seleccionado'],
            ['CC - 1098220593', 'ANYI CAROLINA VEGA BASTO', 'Matriculado'],
            ['TI - 1096950681', 'ANYI LISED CARVAJAL CANDELA', 'No Seleccionado'],
            ['CC - 1096955744', 'CLAUDIA ROCIO CUCHIA JAIMES', 'Convocado Matrícula'],
        ];

        foreach ($data as $rowIndex => $row) {
            foreach ($row as $colIndex => $value) {
                $sheet->setCellValueByColumnAndRow($colIndex + 1, $rowIndex + 7, $value);
            }
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($this->testFilePath);
    }

    public function test_it_processes_individual_ficha_report(): void
    {
        $analyzer = new AnalyzeExcelIndividualByFicha();
        $result = $analyzer->execute($this->testFilePath);

        $this->assertEquals('individual_ficha', $result['report_kind']);
        $this->assertEquals('3410558', $result['metadata']['ficha']);
        $this->assertEquals('GESTION CONTABLE Y DE INFORMACION FINANCIERA', $result['metadata']['programa']);
        $this->assertEquals(5, $result['metadata']['totalAprendices']);
        $this->assertEquals(3, $result['metadata']['totalEstados']);

        $this->assertCount(5, $result['tabla']);
        $this->assertArrayHasKey('Matriculado', $result['estado_totales']);
        $this->assertArrayHasKey('No Seleccionado', $result['estado_totales']);
        $this->assertArrayHasKey('Convocado Matrícula', $result['estado_totales']);
    }
}
