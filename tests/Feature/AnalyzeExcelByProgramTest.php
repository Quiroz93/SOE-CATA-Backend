<?php

namespace Tests\Feature;

use App\Application\Statistics\AnalyzeExcelByProgram;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

class AnalyzeExcelByProgramTest extends TestCase
{
    protected string $testFilePath;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Crear archivo de prueba con datos SENA
        $this->testFilePath = storage_path('app/test_analysis.xlsx');
        $this->createTestExcelFile();
    }

    protected function tearDown(): void
    {
        // Limpiar archivo de prueba
        if (file_exists($this->testFilePath)) {
            unlink($this->testFilePath);
        }
        parent::tearDown();
    }

    /**
     * Crear archivo Excel con estructura SENA (headers en fila 8)
     */
    private function createTestExcelFile(): void
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Filas 1-7: Encabezados metadata SENA (titulo, centro, etc)
        $sheet->setCellValue('A1', 'CENTRO AGROEMPRESARIAL Y TURISTICO DE LOS ANDES');
        $sheet->setCellValue('A2', 'REGIONAL SANTANDER');
        $sheet->setCellValue('A3', '');
        $sheet->setCellValue('A4', 'Reporte de Fichas y Preinscripciones');
        $sheet->setCellValue('A5', '');

        // Fila 8: Encabezados reales (como en archivo SENA)
        $headers = [
            'COD_REGIONAL', 'REGIONAL', 'COD_MUNICIPIO', 'MUNICIPIO',
            'COD_CENTRO', 'CENTRO_FORMACION', 'COD_PROGRAMA', 'DENOMINACION_PROGRAMA',
            'COD_FICHA', 'ESTADO_FICHA', 'JORNADA', 'NIVEL_FORMACION',
            'CUPO', 'INSCRITOS_PRIMERA_OPCION', 'INSCRITOS_SEGUNDA_OPCION'
        ];

        foreach ($headers as $col => $header) {
            $cellAddress = Coordinate::stringFromColumnIndex($col + 1) . '8';
            $sheet->setCellValue($cellAddress, $header);
        }

        // Datos de prueba (filas 9+)
        $data = [
            ['68', 'REGIONAL SANTANDER', '57068432', 'MÁLAGA', '9545', 'CENTRO AGROEMPRESARIAL Y TURISTICO DE LOS ANDES', '225311', 'LEVANTAMIENTOS TOPOGRAFICOS Y GEORREFERENCIACION', '3410569', 'En Selección', 'MIXTA', 'TECNÓLOGO', '30', '95', '0'],
            ['68', 'REGIONAL SANTANDER', '57068160', 'CEPITA', '9545', 'CENTRO AGROEMPRESARIAL Y TURISTICO DE LOS ANDES', '664212', 'EJECUCION DE PROGRAMAS DEPORTIVOS.', '3410546', 'En Matrícula', 'MIXTA', 'TÉCNICO', '30', '34', '0'],
            ['68', 'REGIONAL SANTANDER', '57068432', 'MÁLAGA', '9545', 'CENTRO AGROEMPRESARIAL Y TURISTICO DE LOS ANDES', '123101', 'GESTION CONTABLE Y DE INFORMACION FINANCIERA', '3410558', 'En Matrícula', 'MIXTA', 'TECNÓLOGO', '30', '51', '0'],
            ['68', 'REGIONAL SANTANDER', '57068432', 'MÁLAGA', '9545', 'CENTRO AGROEMPRESARIAL Y TURISTICO DE LOS ANDES', '637200', '.ATENCION INTEGRAL A LA PRIMERA INFANCIA', '3410527', 'En Matrícula', 'MIXTA', 'TÉCNICO', '30', '41', '0'],
            ['68', 'REGIONAL SANTANDER', '57068432', 'MÁLAGA', '9545', 'CENTRO AGROEMPRESARIAL Y TURISTICO DE LOS ANDES', '226701', 'COORDINACION EN SISTEMAS INTEGRADOS DE GESTION', '3410564', 'En Matrícula', 'MIXTA', 'TECNÓLOGO', '30', '39', '0'],
            ['68', 'REGIONAL SANTANDER', '57068207', 'CONCEPCIÓN', '9545', 'CENTRO AGROEMPRESARIAL Y TURISTICO DE LOS ANDES', '225208', 'DIBUJO ARQUITECTÓNICO', '3410525', 'En Selección', 'MIXTA', 'TÉCNICO', '30', '81', '0'],
            ['68', 'REGIONAL SANTANDER', '57068432', 'MÁLAGA', '9545', 'CENTRO AGROEMPRESARIAL Y TURISTICO DE LOS ANDES', '961520', 'PROCESOS DE PANADERIA', '3410523', 'En Matrícula', 'MIXTA', 'OPERARIO', '30', '44', '0'],
            ['68', 'REGIONAL SANTANDER', '57068432', 'MÁLAGA', '9545', 'CENTRO AGROEMPRESARIAL Y TURISTICO DE LOS ANDES', '637804', 'COSMETOLOGIA Y ESTETICA INTEGRAL..', '3410528', 'En Matrícula', 'MIXTA', 'TÉCNICO', '30', '108', '0'],
            ['68', 'REGIONAL SANTANDER', '57068432', 'MÁLAGA', '9545', 'CENTRO AGROEMPRESARIAL Y TURISTICO DE LOS ANDES', '228118', 'ANALISIS Y DESARROLLO DE SOFTWARE.', '3410551', 'En Matrícula', 'MIXTA', 'TECNÓLOGO', '30', '46', '0'],
            ['68', 'REGIONAL SANTANDER', '57068432', 'MÁLAGA', '9545', 'CENTRO AGROEMPRESARIAL Y TURISTICO DE LOS ANDES', '525214', 'ACTIVIDAD FISICA', '3410548', 'En Matrícula', 'MIXTA', 'TECNÓLOGO', '30', '39', '0'],
            ['68', 'REGIONAL SANTANDER', '57068432', 'MÁLAGA', '9545', 'CENTRO AGROEMPRESARIAL Y TURISTICO DE LOS ANDES', '122115', 'GESTIÓN ADMINISTRATIVA', '3410568', 'En Matrícula', 'MIXTA', 'TECNÓLOGO', '30', '51', '0'],
        ];

        foreach ($data as $rowIndex => $row) {
            foreach ($row as $colIndex => $value) {
                $rowNumber = $rowIndex + 9;
                $cellAddress = Coordinate::stringFromColumnIndex($colIndex + 1) . $rowNumber;
                $sheet->setCellValue($cellAddress, $value);
            }
        }

        // Guardar archivo
        $writer = new Xlsx($spreadsheet);
        $writer->save($this->testFilePath);
    }

    /**
     * Verificar que las columnas se detectan correctamente
     */
    public function test_columns_are_detected_correctly(): void
    {
        $analyzer = new AnalyzeExcelByProgram();
        $result = $analyzer->execute($this->testFilePath);

        // Verificar que se detectaron columnas
        $this->assertIsArray($result['metadata']['columnasDetectadas']);
        $this->assertGreaterThan(0, count($result['metadata']['columnasDetectadas']));
        
        // Verificar columnas SENA clave
        $detectadas = $result['metadata']['columnasDetectadas'];
        $this->assertArrayHasKey('ficha', $detectadas);
        $this->assertArrayHasKey('programa', $detectadas);
        $this->assertArrayHasKey('estado', $detectadas);
        $this->assertArrayHasKey('regional', $detectadas);
        $this->assertArrayHasKey('centro', $detectadas);

        echo "\n✓ Columnas detectadas correctamente:";
        foreach ($detectadas as $col => $index) {
            echo "\n  - $col (índice $index)";
        }
    }

    /**
     * Verificar que se agrupan correctamente por COD_FICHA
     */
    public function test_data_grouped_by_cod_ficha(): void
    {
        $analyzer = new AnalyzeExcelByProgram();
        $result = $analyzer->execute($this->testFilePath);

        $this->assertIsArray($result['tabla']);
        $this->assertGreaterThan(0, count($result['tabla']));

        // Verificar que cada entrada tiene ficha y programa
        foreach ($result['tabla'] as $item) {
            $this->assertArrayHasKey('ficha', $item);
            $this->assertArrayHasKey('programa', $item);
            $this->assertStringStartsWith('3410', (string)$item['ficha']);
        }

        echo "\n✓ Fichas únicas detectadas: " . count($result['tabla']);
        echo "\n✓ Total de registros procesados: " . $result['totalRegistros'];
    }

    /**
     * Verificar datos de prueba específicos
     */
    public function test_data_processing_accuracy(): void
    {
        $analyzer = new AnalyzeExcelByProgram();
        $result = $analyzer->execute($this->testFilePath);

        // Verificar totales
        $this->assertEquals(11, $result['totalRegistros'], 'Debe haber 11 registros totales');
        $this->assertEquals(11, count($result['tabla']), 'Debe haber 11 fichas únicas');

        // Total de inscritos: 95+34+51+41+39+81+44+108+46+39+51 = 629
        $this->assertEquals(629, $result['metadata']['totalInscritos']);

        // Total de cupos: 11 fichas × 30 cupos = 330
        $this->assertEquals(330, $result['metadata']['totalCupos']);

        // Ocupación promedio: 629/330 = 190.61%
        $ocupacionPromedio = $result['metadata']['ocupacionPromedio'];
        $this->assertGreaterThan(190, $ocupacionPromedio);
        $this->assertLessThan(191, $ocupacionPromedio);

        echo "\n✓ Validaciones de datos:";
        echo "\n  - Total registros: " . $result['totalRegistros'];
        echo "\n  - Total inscritos: " . $result['metadata']['totalInscritos'];
        echo "\n  - Total cupos: " . $result['metadata']['totalCupos'];
        echo "\n  - Ocupación promedio: " . $ocupacionPromedio . "%";
    }

    /**
     * Verificar que los datos para gráficas son coherentes
     */
    public function test_chart_data_coherence(): void
    {
        $analyzer = new AnalyzeExcelByProgram();
        $result = $analyzer->execute($this->testFilePath);

        // Labels y series deben tener el mismo tamaño
        $this->assertCount(count($result['labels']), $result['series']);
        $this->assertCount(11, $result['labels']);

        // Series deben sumar totalRegistros
        $this->assertEquals($result['totalRegistros'], array_sum($result['series']));

        // Verificar estados por ficha
        foreach ($result['tabla'] as $item) {
            $this->assertIsArray($item['estados']);
            $this->assertTrue(
                in_array('En Selección', array_keys($item['estados'])) || 
                in_array('En Matrícula', array_keys($item['estados'])),
                'Estados debe contener "En Selección" o "En Matrícula"'
            );
        }

        echo "\n✓ Datos para gráficas coherentes:";
        echo "\n  - Labels: " . count($result['labels']);
        echo "\n  - Series sum: " . array_sum($result['series']);
        echo "\n  Programas detectados:";
        foreach ($result['labels'] as $index => $programa) {
            echo "\n    " . ($index + 1) . ". $programa (" . $result['series'][$index] . " registros)";
        }
    }

    /**
     * Verificar centros detectados por ficha
     */
    public function test_centers_detected_per_ficha(): void
    {
        $analyzer = new AnalyzeExcelByProgram();
        $result = $analyzer->execute($this->testFilePath);

        echo "\n✓ Centros detectados por ficha:";
        
        foreach ($result['tabla'] as $item) {
            echo "\n  Ficha {$item['ficha']} ({$item['programa']}):";
            echo "\n    - Centros únicos: {$item['centros_count']}";
            echo "\n    - Top centros: " . implode(', ', $item['centros_top']);
        }

        // Todas las fichas en este dataset están en el mismo centro
        foreach ($result['tabla'] as $item) {
            $this->assertEquals(1, $item['centros_count'], 'Todas las fichas están en 1 centro');
        }
    }

    /**
     * Verificar niveles de formación detectados
     */
    public function test_formation_levels_detected(): void
    {
        $analyzer = new AnalyzeExcelByProgram();
        $result = $analyzer->execute($this->testFilePath);

        $niveles = [];
        foreach ($result['tabla'] as $item) {
            if ($item['nivel']) {
                $niveles[] = $item['nivel'];
            }
        }

        echo "\n✓ Niveles de formación detectados:";
        foreach (array_unique($niveles) as $nivel) {
            echo "\n  - $nivel";
        }

        $this->assertContains('TECNÓLOGO', $niveles);
        $this->assertContains('TÉCNICO', $niveles);
        $this->assertContains('OPERARIO', $niveles);
    }

    /**
     * Verificar estados de ficha detectados
     */
    public function test_ficha_states_detected(): void
    {
        $analyzer = new AnalyzeExcelByProgram();
        $result = $analyzer->execute($this->testFilePath);

        $todosLosEstados = [];
        foreach ($result['tabla'] as $item) {
            foreach (array_keys($item['estados']) as $estado) {
                $todosLosEstados[$estado] = ($todosLosEstados[$estado] ?? 0) + 1;
            }
        }

        echo "\n✓ Estados de ficha detectados:";
        foreach ($todosLosEstados as $estado => $total) {
            echo "\n  - $estado: $total registros";
        }

        $this->assertArrayHasKey('En Selección', $todosLosEstados);
        $this->assertArrayHasKey('En Matrícula', $todosLosEstados);
    }
}
