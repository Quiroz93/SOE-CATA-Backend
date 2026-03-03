<?php

namespace App\Console\Commands;

use App\Application\Statistics\AnalyzeExcelByProgram;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Console\Command;

class TestAnalyzeExcel extends Command
{
    protected $signature = 'test:analyze-excel';
    protected $description = 'Prueba el análisis de archivos Excel con datos SENA';

    public function handle()
    {
        $this->info('🔍 Probando análisis de archivo Excel SENA...');
        $this->newLine();

        // Crear archivo de prueba
        $testFile = storage_path('app/test_sena_analysis.xlsx');
        $this->createTestExcelFile($testFile);
        $this->info("✓ Archivo de prueba creado: $testFile");

        try {
            // Analizar archivo
            $analyzer = new AnalyzeExcelByProgram();
            $result = $analyzer->execute($testFile);

            // Mostrar columnas detectadas
            $this->line("\n📋 <fg=cyan>COLUMNAS DETECTADAS:</>");
            foreach ($result['metadata']['columnasDetectadas'] as $col => $index) {
                $this->line("  • $col (índice $index)");
            }

            // Mostrar metadata
            $this->line("\n📊 <fg=cyan>METADATA:</>");
            $this->table(
                ['Métrica', 'Valor'],
                [
                    ['Total Fichas', $result['metadata']['totalFichas']],
                    ['Total Registros', $result['metadata']['totalRegistros']],
                    ['Total Inscritos', $result['metadata']['totalInscritos']],
                    ['Total Cupos', $result['metadata']['totalCupos']],
                    ['Ocupación Promedio', $result['metadata']['ocupacionPromedio'] . '%'],
                ]
            );

            // Mostrar fichas detectadas
            $this->line("\n🎓 <fg=cyan>FICHAS DETECTADAS:</>");
            $tableData = [];
            foreach ($result['tabla'] as $item) {
                $tableData[] = [
                    $item['ficha'],
                    $item['programa'],
                    $item['total'],
                    $item['inscritos'],
                    $item['cupos'],
                    $item['ocupacion'] . '%',
                    $item['nivel'],
                ];
            }

            $this->table(
                ['COD_FICHA', 'PROGRAMA', 'Registros', 'Inscritos', 'Cupos', 'Ocupación', 'Nivel'],
                $tableData
            );

            // Mostrar estados por ficha
            $this->line("\n🔄 <fg=cyan>ESTADOS POR FICHA:</>");
            foreach ($result['tabla'] as $item) {
                $estados = implode(', ', array_map(
                    fn($e, $c) => "$e ($c)",
                    array_keys($item['estados']),
                    array_values($item['estados'])
                ));
                $this->line("  Ficha {$item['ficha']}: $estados");
            }

            // Mostrar centros detectados
            $this->line("\n🏢 <fg=cyan>CENTROS DETECTADOS:</>");
            $centrosUnicos = [];
            foreach ($result['tabla'] as $item) {
                foreach ($item['centros_top'] as $centro) {
                    if (!in_array($centro, $centrosUnicos)) {
                        $centrosUnicos[] = $centro;
                    }
                }
            }
            foreach ($centrosUnicos as $centro) {
                $this->line("  • Centro: $centro");
            }

            // Mostrar niveles detectados
            $this->line("\n📚 <fg=cyan>NIVELES DE FORMACIÓN:</>");
            $niveles = [];
            foreach ($result['tabla'] as $item) {
                if ($item['nivel'] && !in_array($item['nivel'], $niveles)) {
                    $niveles[] = $item['nivel'];
                }
            }
            foreach ($niveles as $nivel) {
                $this->line("  • $nivel");
            }

            // Validar datos para gráficas
            $this->line("\n✅ <fg=green>VALIDACIONES PARA GRÁFICAS:</>");
            
            $validation = true;
            
            // Validar que labels y series tengan el mismo tamaño
            if (count($result['labels']) === count($result['series'])) {
                $this->line("  ✓ Labels y series tienen el mismo tamaño (" . count($result['labels']) . ")");
            } else {
                $this->line("  ✗ ERR: Labels ({" . count($result['labels']) . "}) y series (" . count($result['series']) . ") tienen tamaño diferente");
                $validation = false;
            }

            // Validar suma de series
            $seriesSum = array_sum($result['series']);
            if ($seriesSum === $result['totalRegistros']) {
                $this->line("  ✓ Suma de series = Total registros ($seriesSum)");
            } else {
                $this->line("  ✗ ERR: Suma de series ($seriesSum) ≠ Total registros ({$result['totalRegistros']})");
                $validation = false;
            }

            // Validar que tabla tenga datos
            if (count($result['tabla']) > 0) {
                $this->line("  ✓ Tabla de resultados contiene " . count($result['tabla']) . " fichas");
            } else {
                $this->line("  ✗ ERR: Tabla vacía");
                $validation = false;
            }

            // Validar ocupación
            if ($result['metadata']['ocupacionPromedio'] > 100) {
                $this->line("  ⚠ Ocupación promedio es " . $result['metadata']['ocupacionPromedio'] . "% (supera capacidad)");
            } else {
                $this->line("  ✓ Ocupación promedio: " . $result['metadata']['ocupacionPromedio'] . "%");
            }

            // Mostrar datos para gráfica
            $this->line("\n📈 <fg=cyan>DATOS PARA GRÁFICAS:</>");
            $this->line("  Labels (programa): " . json_encode($result['labels']));
            $this->line("  Series (registros): " . json_encode($result['series']));

            if ($validation) {
                $this->info("\n✓ Todos los datos son coherentes para generar gráficas");
            } else {
                $this->error("\n✗ Hay problemas con los datos para las gráficas");
            }

            // Limpiar
            unlink($testFile);

        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            if (file_exists($testFile)) {
                unlink($testFile);
            }
        }
    }

    private function createTestExcelFile(string $filePath): void
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Filas 1-7: Encabezados metadata (simular SENA)
        $sheet->setCellValue('A1', 'CENTRO AGROEMPRESARIAL Y TURISTICO DE LOS ANDES');
        $sheet->setCellValue('A2', 'REGIONAL SANTANDER');
        $sheet->setCellValue('A4', 'Reporte de Fichas y Preinscripciones');

        // Fila 8: Encabezados reales
        $headers = [
            'COD_REGIONAL', 'REGIONAL', 'COD_MUNICIPIO', 'MUNICIPIO',
            'COD_CENTRO', 'CENTRO_FORMACION', 'COD_PROGRAMA', 'DENOMINACION_PROGRAMA',
            'COD_FICHA', 'ESTADO_FICHA', 'JORNADA', 'NIVEL_FORMACION',
            'CUPO', 'INSCRITOS_PRIMERA_OPCION', 'INSCRITOS_SEGUNDA_OPCION'
        ];

        foreach ($headers as $col => $header) {
            $sheet->setCellValueByColumnAndRow($col + 1, 8, $header);
        }

        // Datos (filas 9+)
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
                $sheet->setCellValueByColumnAndRow($colIndex + 1, $rowIndex + 9, $value);
            }
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);
    }
}
