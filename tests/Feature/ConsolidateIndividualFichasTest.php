<?php

namespace Tests\Feature;

use App\Application\Statistics\ConsolidateIndividualFichas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

class ConsolidateIndividualFichasTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Crear archivo Excel de prueba para una ficha
     */
    private function createTestExcelFile($codigo, $programa, $aprendices)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Fila 1: vacía
        // Fila 2: vacía
        // Fila 3: Código de Ficha
        $sheet->setCellValue('A3', 'Código Ficha');
        $sheet->setCellValue('B3', $codigo);

        // Fila 4: Programa de Formación
        $sheet->setCellValue('A4', 'Programa de Formación');
        $sheet->setCellValue('B4', $programa);

        // Fila 5: vacía
        // Fila 6: Encabezados
        $sheet->setCellValue('A6', 'Identificación');
        $sheet->setCellValue('B6', 'Nombre');
        $sheet->setCellValue('C6', 'Estado');

        // Filas 7 en adelante: aprendices
        foreach ($aprendices as $idx => $aprendiz) {
            $row = 7 + $idx;
            $sheet->setCellValue("A{$row}", $aprendiz['identificacion']);
            $sheet->setCellValue("B{$row}", $aprendiz['nombre']);
            $sheet->setCellValue("C{$row}", $aprendiz['estado']);
        }

        // Guardar en archivo temporal
        $tempFile = tempnam(sys_get_temp_dir(), 'test_ficha_');
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile . '.xlsx');

        return $tempFile . '.xlsx';
    }

    /**
     * Test: Consolidar un único archivo
     */
    public function test_consolidate_single_file()
    {
        $aprendices = [
            ['identificacion' => 'CC - 1096950213', 'nombre' => 'ANGEL HERNANDEZ URIBE', 'estado' => 'Matriculado'],
            ['identificacion' => 'CC - 1007917194', 'nombre' => 'ANGIE DANIELA JAIMES SANDOVAL', 'estado' => 'No Seleccionado'],
            ['identificacion' => 'CC - 1098220593', 'nombre' => 'ANYI CAROLINA VEGA BASTO', 'estado' => 'Matriculado'],
        ];

        $file = $this->createTestExcelFile('3410558', 'GESTION CONTABLE Y DE INFORMACIÓN FINANCIERA', $aprendices);

        $consolidator = new ConsolidateIndividualFichas();
        $result = $consolidator->execute([$file]);

        // Validaciones
        $this->assertTrue($result['consolidado']);
        $this->assertEquals(1, $result['totales']['fichas']);
        $this->assertEquals(3, $result['totales']['aprendices']);
        $this->assertEquals(2, $result['totales']['estados']);
        $this->assertArrayHasKey('fichas', $result);
        $this->assertArrayHasKey('3410558', $result['fichas']);

        // Validar estructura de ficha
        $ficha = $result['fichas']['3410558'];
        $this->assertEquals('3410558', $ficha['ficha']);
        $this->assertCount(3, $ficha['aprendices']);
        $this->assertEquals(2, count($ficha['estadoCounts']));

        // Limpiar archivo temporal
        @unlink($file);
    }

    /**
     * Test: Consolidar múltiples archivos
     */
    public function test_consolidate_multiple_files()
    {
        $ficha1 = $this->createTestExcelFile(
            '3410558',
            'GESTION CONTABLE Y DE INFORMACIÓN FINANCIERA',
            [
                ['identificacion' => 'CC - 1096950213', 'nombre' => 'ANGEL HERNANDEZ URIBE', 'estado' => 'Matriculado'],
                ['identificacion' => 'CC - 1007917194', 'nombre' => 'ANGIE DANIELA JAIMES SANDOVAL', 'estado' => 'No Seleccionado'],
            ]
        );

        $ficha2 = $this->createTestExcelFile(
            '3410559',
            'SISTEMAS INFORMÁTICOS',
            [
                ['identificacion' => 'CC - 1098220593', 'nombre' => 'ANYI CAROLINA VEGA BASTO', 'estado' => 'Matriculado'],
                ['identificacion' => 'TI - 1096950681', 'nombre' => 'ANYI LISED CARVAJAL CANDELA', 'estado' => 'Convocado Matrícula'],
                ['identificacion' => 'CC - 1098150655', 'nombre' => 'BRIYITH YURANY VARGAS CARVAJAL', 'estado' => 'Matriculado'],
            ]
        );

        $consolidator = new ConsolidateIndividualFichas();
        $result = $consolidator->execute([$ficha1, $ficha2]);

        // Validaciones
        $this->assertTrue($result['consolidado']);
        $this->assertEquals(2, $result['totales']['fichas']);
        $this->assertEquals(5, $result['totales']['aprendices']);
        $this->assertEquals(3, $result['totales']['estados']);

        // Validar estados globales
        $this->assertEquals(3, $result['estados_globales']['Matriculado']);
        $this->assertEquals(1, $result['estados_globales']['No Seleccionado']);
        $this->assertEquals(1, $result['estados_globales']['Convocado Matrícula']);

        // Validar que ambas fichas están en el resultado
        $this->assertArrayHasKey('3410558', $result['fichas']);
        $this->assertArrayHasKey('3410559', $result['fichas']);

        // Validar conteos por ficha
        $this->assertCount(2, $result['fichas']['3410558']['aprendices']);
        $this->assertCount(3, $result['fichas']['3410559']['aprendices']);

        // Limpiar archivos temporales
        @unlink($ficha1);
        @unlink($ficha2);
    }

    /**
     * Test: Consolidación con archivos vacíos (debe ignorarlos)
     */
    public function test_consolidate_handles_invalid_files()
    {
        $validFile = $this->createTestExcelFile(
            '3410558',
            'GESTION CONTABLE Y DE INFORMACIÓN FINANCIERA',
            [
                ['identificacion' => 'CC - 1096950213', 'nombre' => 'ANGEL HERNANDEZ URIBE', 'estado' => 'Matriculado'],
            ]
        );

        $consolidator = new ConsolidateIndividualFichas();
        $result = $consolidator->execute([$validFile, '/ruta/inexistente.xlsx']);

        // Debe procesar correctamente ignorando el archivo inválido
        $this->assertTrue($result['consolidado']);
        $this->assertEquals(1, $result['totales']['fichas']);

        @unlink($validFile);
    }

    /**
     * Test: Error cuando no hay archivos válidos
     */
    public function test_consolidate_throws_error_on_invalid_files()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No se pudieron procesar correctamente');

        $consolidator = new ConsolidateIndividualFichas();
        $consolidator->execute(['/archivo/no/existe.xlsx']);
    }

    /**
     * Test: Error cuando no hay archivos
     */
    public function test_consolidate_throws_error_on_empty_array()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No se proporcionaron archivos');

        $consolidator = new ConsolidateIndividualFichas();
        $consolidator->execute([]);
    }

    /**
     * Test: Endpoint de API para consolidación
     */
    public function test_api_consolidate_endpoint()
    {
        // Crear un usuario autenticado
        $user = \App\Models\User::factory()->create();

        $ficha1 = $this->createTestExcelFile(
            '3410558',
            'GESTION CONTABLE Y DE INFORMACIÓN FINANCIERA',
            [
                ['identificacion' => 'CC - 1096950213', 'nombre' => 'ANGEL HERNANDEZ URIBE', 'estado' => 'Matriculado'],
            ]
        );

        $ficha2 = $this->createTestExcelFile(
            '3410559',
            'SISTEMAS INFORMÁTICOS',
            [
                ['identificacion' => 'CC - 1098220593', 'nombre' => 'ANYI CAROLINA VEGA BASTO', 'estado' => 'Matriculado'],
            ]
        );

        // Sin autenticación, debería dar error 401
        $response = $this->postJson('/admin/dashboard/estadisticas/upload', [
            'report_kind' => 'individual_ficha_consolidado',
        ]);

        $response->assertStatus(401);

        // Con autenticación, debería procesar
        $responseAuth = $this->actingAs($user)->postJson('/admin/dashboard/estadisticas/upload', [
            'report_kind' => 'individual_ficha_consolidado',
        ]);

        // Debería retornar 422 por no tener archivos
        $responseAuth->assertStatus(422);

        @unlink($ficha1);
        @unlink($ficha2);
    }
}
