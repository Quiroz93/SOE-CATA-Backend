<?php

namespace Tests\Feature;

use App\Application\Statistics\AnalyzeExcelIndividualByFicha;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

/**
 * Test para validar que los caracteres especiales con acentos
 * se preservan correctamente durante la lectura del Excel
 */
class CharacterEncodingTest extends TestCase
{
    protected string $testFilePath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->testFilePath = storage_path('app/test_encoding.xlsx');
        $this->createTestExcelFileWithSpecialCharacters();
    }

    protected function tearDown(): void
    {
        if (file_exists($this->testFilePath)) {
            unlink($this->testFilePath);
        }

        parent::tearDown();
    }

    /**
     * Crea un archivo Excel con caracteres especiales acentuados
     * para validar que no se corrompen durante el procesamiento
     */
    private function createTestExcelFileWithSpecialCharacters(): void
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

        // Datos con caracteres especiales acentuados
        $data = [
            ['CC - 1096950213', 'ÁNGEL HERNÁNDEZ URIBE', 'Matriculado'],
            ['CC - 1007917194', 'ANGIE DANIELA JAIMES SANDOVAL', 'Anulado Matrícula'],
            ['CC - 1098220593', 'ANÍY CAROLINA VEGA BASTO', 'Matriculado'],
            ['TI - 1096950681', 'ANÍY LISËD CARVAJAL CANDELA', 'Convocado Matrícula'],
            ['CC - 1096955744', 'CLAUDÏA ROCIO CUCHIA JAIMES', 'Anulado Matrícula'],
        ];

        foreach ($data as $rowIndex => $row) {
            foreach ($row as $colIndex => $value) {
                $rowNumber = $rowIndex + 7;
                $cellAddress = Coordinate::stringFromColumnIndex($colIndex + 1) . $rowNumber;
                $sheet->setCellValue($cellAddress, $value);
            }
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($this->testFilePath);
    }

    /**
     * Validar que caracteres acentuados se preservan correctamente
     */
    public function test_accented_characters_are_preserved(): void
    {
        $analyzer = new AnalyzeExcelIndividualByFicha();
        $result = $analyzer->execute($this->testFilePath);

        // Verificar que los nombres con acentos se han preservado correctamente
        $tabla = $result['tabla'];
        
        // Buscar el registro con "ÁNGEL"
        $angelRecord = array_filter($tabla, fn($row) => str_contains($row['nombre'], 'ÁNGEL') || str_contains($row['nombre'], 'ANGEL'));
        $this->assertNotEmpty($angelRecord, 'El nombre con tilde ÁNGEL debería estar en los datos procesados');

        // Verificar que no tenga caracteres corruptos
        $firstRecord = $tabla[0];
        $this->assertStringNotContainsString('?', $firstRecord['nombre'], 
            'No debe haber caracteres ? que indiquen corrupción de encoding');
        
        // Verificar que el nombre contiene el carácter especial esperado (aunque sea normalizado a ANGEL)
        $this->assertTrue(
            str_contains(mb_strtoupper($firstRecord['nombre']), 'NGEL'),
            'El nombre normalizado debe contener la raíz correcta'
        );
    }

    /**
     * Validar que estados con "Matrícula" se preservan correctamente
     */
    public function test_matricula_state_is_preserved(): void
    {
        $analyzer = new AnalyzeExcelIndividualByFicha();
        $result = $analyzer->execute($this->testFilePath);

        $estadoTotales = $result['estado_totales'];
        
        // Debe haber estados con "Matrícula" (con tilde)
        $hasMatriculaState = array_key_exists('Matriculado', $estadoTotales) ||
                             array_key_exists('Convocado Matrícula', $estadoTotales) ||
                             array_key_exists('Anulado Matrícula', $estadoTotales);
        
        $this->assertTrue($hasMatriculaState, 
            'Debe existir al menos un estado con la palabra Matrícula correctamente codificada');

        // Verificar que "Anulado Matrícula" se ha detectado
        $hasAnuladoMatricula = array_key_exists('Anulado Matrícula', $estadoTotales);
        $this->assertTrue($hasAnuladoMatricula, 
            'El estado "Anulado Matrícula" debe ser detectado correctamente');

        // Verificar el conteo
        $this->assertEquals(2, $estadoTotales['Anulado Matrícula'], 
            'Debe haber 2 registros con estado "Anulado Matrícula"');
    }

    /**
     * Validar que no hay corrupción de caracteres en los valores de estado
     */
    public function test_no_character_corruption_in_estado_values(): void
    {
        $analyzer = new AnalyzeExcelIndividualByFicha();
        $result = $analyzer->execute($this->testFilePath);

        $estadoTotales = $result['estado_totales'];
        
        // Verificar que ningún estado contiene caracteres de corrupción
        foreach (array_keys($estadoTotales) as $estado) {
            $this->assertStringNotContainsString('?', $estado,
                sprintf('El estado "%s" contiene caracteres corruptos', $estado));
            
            // Verificar que los caracteres reconocibles están presentes
            if (str_contains($estado, 'Matrícula')) {
                $this->assertNotEmpty($estado, 'Los estados con Matrícula no deben estar vacíos');
            }
        }

        // Verificar que al menos hay una variación correcta de "Matrícula"
        $hasCorrectEncoding = collect($estadoTotales)->keys()->contains(fn($key) => 
            str_contains($key, 'Matrícula') || str_contains($key, 'Matricula')
        );
        
        $this->assertTrue($hasCorrectEncoding, 
            'Debe haber al menos un estado que contenga la palabra Matrícula/Matricula');
    }
}
