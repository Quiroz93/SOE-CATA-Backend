<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Programa\Enums\EstadoPreinscrito;
use App\Http\Controllers\Controller;
use App\Models\Oferta;
use App\Models\Preinscrito;
use App\Models\OfertaPrograma;
use App\Models\Programa;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class PreinscritorImportExportController extends Controller
{
    /**
     * Descargar plantilla Excel estándar para recopilación de preinscritos
     */
    public function downloadTemplate()
    {
        // Obtener programas activos/publicados de la DB
        $programas = Programa::where('estado', 'publicado')
            ->orderBy('nombre')
            ->pluck('nombre')
            ->toArray();
        
        // Tipos de documento válidos
        $tiposDocumento = ['CC', 'TI', 'CE', 'PAS', 'PPT'];
        
        // Estados válidos
        $estados = EstadoPreinscrito::values();
        $estadoPorDefecto = EstadoPreinscrito::tryFromInput('pendiente')?->value ?? ($estados[0] ?? '');
        
        // Crear nuevo spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Configurar ancho de columnas
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(30);
        $sheet->getColumnDimension('F')->setWidth(30);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(18);
        $sheet->getColumnDimension('I')->setWidth(40);
        
        // ============================================
        // LOGO SENA (Alineado a la izquierda)
        // ============================================
        $logoPath = public_path('logo-sena.png');
        
        if (file_exists($logoPath)) {
            $drawing = new Drawing();
            $drawing->setName('Logo SENA');
            $drawing->setDescription('Logo del SENA');
            $drawing->setPath($logoPath);
            $drawing->setCoordinates('A1');
            $drawing->setHeight(50);
            $drawing->setOffsetX(5);
            $drawing->setOffsetY(5);
            $drawing->setWorksheet($sheet);
        }
        
        // ============================================
        // ENCABEZADO INSTITUCIONAL
        // ============================================
        // Fusionar celdas para encabezado (B1:G1)
        $sheet->mergeCells('B1:G1');
        $headerCell = $sheet->getCell('B1');
        $headerCell->setValue('CENTRO AGROEMPRESARIAL Y TURÍSTICO DE LOS ANDES - SENA');
        
        $headerCell->getStyle()
            ->getFont()
            ->setSize(14)
            ->setBold(true)
            ->setColor(new Color('FFFFFF'));
        
        $headerCell->getStyle()
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('39A900'); // Verde SENA
        
        $headerCell->getStyle()
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);
        
        $sheet->getRowDimension('1')->setRowHeight(30);
        
        // ============================================
        // SUBTÍTULO CON FECHA DE ACTUALIZACIÓN
        // ============================================
        $sheet->mergeCells('A2:H2');
        $subtitleCell = $sheet->getCell('A2');
        $fechaActualizacion = now()->format('d/m/Y H:i');
        $subtitleCell->setValue('PLANTILLA ESTÁNDAR DE RECOPILACIÓN DE PREINSCRITOS - Actualizada: ' . $fechaActualizacion);
        
        $subtitleCell->getStyle()
            ->getFont()
            ->setSize(10)
            ->setBold(true)
            ->setColor(new Color('00304D')); // Azul SENA
        
        $subtitleCell->getStyle()
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);
        
        $subtitleCell->getStyle()
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('E8F5E9'); // Verde muy claro
        
        $sheet->getRowDimension('2')->setRowHeight(22);
        
        // Espacio en blanco
        $sheet->getRowDimension('3')->setRowHeight(8);
        
        // ============================================
        // ENCABEZADOS DE TABLA
        // ============================================
        $headers = ['Nombres', 'Apellidos', 'Tipo Documento', 'Documento', 'Correo Electrónico', 'Programa', 'Número Ficha', 'Estado'];
        
        for ($i = 0; $i < count($headers); $i++) {
            $column = chr(65 + $i);
            $cell = $sheet->getCell($column . '4');
            $cell->setValue($headers[$i]);
            
            $cell->getStyle()
                ->getFont()
                ->setBold(true)
                ->setSize(11)
                ->setColor(new Color('FFFFFF'));
            
            $cell->getStyle()
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('00304D'); // Azul institucional
            
            $cell->getStyle()
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER)
                ->setWrapText(true);
            
            $cell->getStyle()
                ->getBorders()
                ->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN)
                ->setColor(new Color('E0E0E0'));
        }
        
        $sheet->getRowDimension('4')->setRowHeight(24);
        
        // ============================================
        // DATOS DE EJEMPLO CON LISTAS DESPLEGABLES
        // ============================================
        // DATOS DE EJEMPLO CON LISTAS DESPLEGABLES
        // ============================================
        $programaEjemplo = $programas[0] ?? 'Técnico en Agronomía';
        
        $exampleData = [
            ['Juan Carlos', 'Pérez López', 'CC', '1234567890', 'juan.perez@example.com', $programaEjemplo, '', $estadoPorDefecto],
            ['María Isabel', 'García Rodríguez', 'CC', '0987654321', 'maria.garcia@example.com', $programaEjemplo, '', $estadoPorDefecto],
            ['Pedro Antonio', 'López Martínez', 'TI', '5555555555', 'pedro.lopez@example.com', $programaEjemplo, '', $estadoPorDefecto],
        ];
        
        $row = 5;
        foreach ($exampleData as $data) {
            for ($i = 0; $i < count($data); $i++) {
                $column = chr(65 + $i);
                $cell = $sheet->getCell($column . $row);
                $cell->setValue($data[$i]);
                
                $cell->getStyle()
                    ->getFont()
                    ->setSize(10)
                    ->setColor(new Color('333333'));
                
                $cell->getStyle()
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_LEFT)
                    ->setVertical(Alignment::VERTICAL_CENTER);
                
                $cell->getStyle()
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN)
                    ->setColor(new Color('E0E0E0'));
            }
            
            $sheet->getRowDimension($row)->setRowHeight(20);
            $row++;
        }
        
        // ============================================
        // CREAR HOJA OCULTA PARA LISTAS DE VALIDACIÓN
        // ============================================
        
        // Crear hoja adicional para almacenar las listas
        $validationSheet = $spreadsheet->createSheet();
        $validationSheet->setTitle('Datos_Validacion');
        
        // Escribir programas y sus fichas en la hoja de validación (columnas A y B)
        $programasConFicha = Programa::where('estado', 'publicado')
            ->orderBy('nombre')
            ->select('nombre', 'ficha')
            ->get();
        
        $row = 1;
        foreach ($programasConFicha as $prog) {
            $validationSheet->setCellValue('A' . $row, $prog->nombre);
            $validationSheet->setCellValue('B' . $row, $prog->ficha);
            $row++;
        }
        
        // Escribir tipos de documento en la hoja de validación (columna C)
        $row = 1;
        foreach ($tiposDocumento as $tipo) {
            $validationSheet->setCellValue('C' . $row, $tipo);
            $row++;
        }
        
        // Escribir estados en la hoja de validación (columna D)
        $row = 1;
        foreach ($estados as $estado) {
            $validationSheet->setCellValue('D' . $row, $estado);
            $row++;
        }
        
        // Ocultar la hoja de validación
        $validationSheet->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);
        
        // Volver a la hoja principal
        $spreadsheet->setActiveSheetIndex(0);
        
        // ============================================
        // LISTAS DESPLEGABLES (DATA VALIDATION)
        // ============================================
        
        // Calcular rangos dinámicos basados en la cantidad de elementos
        $programasCount = count($programasConFicha);
        $tiposDocumentoCount = count($tiposDocumento);
        $estadosCount = count($estados);
        
        // Aplicar validación a las primeras 100 filas (desde fila 5 hasta 104)
        // Columna C: Tipo de Documento
        for ($i = 5; $i <= 104; $i++) {
            $validation = $sheet->getCell('C' . $i)->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Entrada inválida');
            $validation->setError('Por favor seleccione un tipo de documento de la lista.');
            $validation->setPromptTitle('Tipo de Documento');
            $validation->setPrompt('Seleccione: CC, TI, CE, PAS o PPT.');
            $validation->setFormula1('Datos_Validacion!$C$1:$C$' . $tiposDocumentoCount);
        }
        
        // Columna F: Programas
        for ($i = 5; $i <= 104; $i++) {
            $validation = $sheet->getCell('F' . $i)->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Entrada inválida');
            $validation->setError('Por favor seleccione un programa de la lista.');
            $validation->setPromptTitle('Programa');
            $validation->setPrompt('Seleccione el programa de formación.');
            $validation->setFormula1('Datos_Validacion!$A$1:$A$' . $programasCount);
        }
        
        // Columna G: Número Ficha (VLOOKUP automático)
        for ($i = 5; $i <= 104; $i++) {
            $cell = $sheet->getCell('G' . $i);
            $cell->setValue('=IFERROR(VLOOKUP(F' . $i . ',Datos_Validacion!$A$1:$B$' . $programasCount . ',2,FALSE),"")');
            $cell->getStyle()->getNumberFormat()->setFormatCode('0');
        }
        
        // Columna H: Estado
        for ($i = 5; $i <= 104; $i++) {
            $validation = $sheet->getCell('H' . $i)->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Entrada inválida');
            $validation->setError('Por favor seleccione un estado válido de la lista: ' . implode(', ', $estados) . '.');
            $validation->setPromptTitle('Estado');
            $validation->setPrompt('Seleccione el estado del preinscrito.');
            $validation->setFormula1('Datos_Validacion!$D$1:$D$' . $estadosCount);
        }
        
        // ============================================
        // INSTRUCCIONES
        // ============================================
        $instructionsTitle = $sheet->getCell('I9');
        $instructionsTitle->setValue('INSTRUCCIONES DE USO');
        
        $instructionsTitle->getStyle()
            ->getFont()
            ->setBold(true)
            ->setSize(11)
            ->setColor(new Color('39A900'));
        
        $sheet->getRowDimension('9')->setRowHeight(18);
        
        $programasDisponibles = !empty($programas) ? implode(', ', array_slice($programas, 0, 3)) . '...' : 'Consulte la lista desplegable';
        
        $instructions = [
            '• Nombres: Ingrese el primer nombre y middle name del preinscrito (si aplica)',
            '• Apellidos: Ingrese el(los) apellido(s) del preinscrito',
            '• Tipo Documento: SELECCIONE de lista (CC=Cedula, TI=Tarjeta Identidad, CE=Cedula Extranjeria, PAS=Pasaporte, PPT=Permiso Proteccion)',
            '• Documento: Ingrese numero de cedula o documento (sin puntos ni espacios)',
            '• Correo Electronico: Ingrese correo valido y activo',
            '• Programa: SELECCIONE de la lista. Disponibles: ' . $programasDisponibles,
            '• Numero Ficha: AUTOMATICAMENTE al seleccionar programa (NO editar)',
            '• Estado: SELECCIONE de lista (' . implode(', ', $estados) . ')',
            '',
            'IMPORTANTE: Nombres, Apellidos, Tipo Documento, Documento y Correo son OBLIGATORIOS',
            'No modifique los encabezados de las columnas',
            'Use las listas desplegables en: Tipo Documento, Programa y Estado',
            'El Numero Ficha se completa automaticamente, NO lo edite',
        ];
        
        $instructionRow = 10;
        foreach ($instructions as $instruction) {
            $cell = $sheet->getCell('I' . $instructionRow);
            $cell->setValue($instruction);
            
            $cell->getStyle()
                ->getFont()
                ->setSize(9)
                ->setColor(new Color('333333'));
            
            $cell->getStyle()
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_LEFT)
                ->setVertical(Alignment::VERTICAL_TOP)
                ->setWrapText(true);
            
            $sheet->getRowDimension($instructionRow)->setRowHeight(16);
            $instructionRow++;
        }
        
        // Crear archivo y descargar
        $filename = 'Plantilla_Preinscritos_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }
    
    /**
     * Mostrar formulario de importación
     */
    public function showImportForm()
    {
        $ofertas = Oferta::orderByDesc('id')
            ->get();

        return view('admin.preinscritos.import', compact('ofertas'));
    }
    
    /**
     * Procesar importación desde Excel
     */
    public function handleImport(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:5120', // 5MB máximo
            'oferta_id' => 'required|integer|exists:ofertas,id',
        ]);
        
        try {
            $file = $request->file('excel_file');
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            
            $rows = $sheet->toArray();
            $imported = 0;
            $failed = 0;
            $resultadosPorFila = [];
            $estadoPorDefecto = EstadoPreinscrito::tryFromInput('pendiente')?->value
                ?? (EstadoPreinscrito::values()[0] ?? '');

            $ofertaDestino = Oferta::find($request->integer('oferta_id'));

            if (!$ofertaDestino) {
                return redirect()
                    ->route('admin.preinscritos.showImportForm')
                    ->with('error', 'La oferta seleccionada no es válida para importar.');
            }

            $ofertasProgramaDeOferta = OfertaPrograma::with('programa')
                ->where('oferta_id', $ofertaDestino->id)
                ->get();

            if ($ofertasProgramaDeOferta->isEmpty()) {
                return redirect()
                    ->route('admin.preinscritos.showImportForm')
                    ->with('error', 'La oferta seleccionada no tiene programas asociados para importar.');
            }

            $normalizarFicha = static function (string $ficha): string {
                return preg_replace('/\s+/', '', mb_strtolower(trim($ficha)));
            };

            $ofertaProgramaPorFicha = $ofertasProgramaDeOferta
                ->filter(static fn (OfertaPrograma $registro): bool => !empty($registro->programa?->ficha))
                ->keyBy(static fn (OfertaPrograma $registro): string => $normalizarFicha((string) $registro->programa->ficha));

            $registrarResultado = static function (array &$resultadosPorFila, int $excelRow, array $baseData, bool $success, string $message): void {
                $resultadosPorFila[] = [
                    'excel_row' => $excelRow,
                    'data' => $baseData,
                    'success' => $success,
                    'message' => $message,
                ];
            };
            
            // Saltar encabezados (primeras 4 filas son encabezado)
            for ($i = 5; $i < count($rows); $i++) {
                $row = $rows[$i];
                
                // Validar que la fila tenga datos
                if (empty(trim($row[0] ?? ''))) {
                    continue; // Saltar filas vacías
                }
                
                $nombre = trim($row[0] ?? '');
                $apellido = trim($row[1] ?? '');
                $tipoDocumento = trim($row[2] ?? '');
                $documento = trim($row[3] ?? '');
                $correo = trim($row[4] ?? '');
                $programaNombre = trim($row[5] ?? '');
                $numeroFicha = trim((string) ($row[6] ?? ''));
                $estado = trim((string) ($row[7] ?? $estadoPorDefecto));

                $baseData = [
                    'nombre' => $nombre,
                    'apellido' => $apellido,
                    'tipo_documento' => $tipoDocumento,
                    'documento' => $documento,
                    'correo' => $correo,
                    'programa' => $programaNombre,
                    'numero_ficha' => $numeroFicha,
                    'estado' => $estado,
                ];
                
                // Validaciones básicas
                if (!$nombre || !$apellido || !$tipoDocumento || !$documento || !$correo) {
                    $failed++;
                    $registrarResultado(
                        $resultadosPorFila,
                        $i + 1,
                        $baseData,
                        false,
                        'Fallo: Datos incompletos. Nombres, Apellidos, Tipo Documento, Documento y Correo son obligatorios.'
                    );
                    continue;
                }
                
                // Validar tipo de documento
                $tiposValidos = ['CC', 'TI', 'CE', 'PAS', 'PPT'];
                if (!in_array($tipoDocumento, $tiposValidos)) {
                    $failed++;
                    $registrarResultado(
                        $resultadosPorFila,
                        $i + 1,
                        $baseData,
                        false,
                        "Fallo: Tipo de documento inválido ($tipoDocumento). Use: CC, TI, CE, PAS o PPT."
                    );
                    continue;
                }
                
                if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                    $failed++;
                    $registrarResultado(
                        $resultadosPorFila,
                        $i + 1,
                        $baseData,
                        false,
                        "Fallo: Correo inválido ($correo)."
                    );
                    continue;
                }

                if ($numeroFicha === '') {
                    $failed++;
                    $registrarResultado(
                        $resultadosPorFila,
                        $i + 1,
                        $baseData,
                        false,
                        'Fallo: Número Ficha es obligatorio para relacionar el programa dentro de la oferta seleccionada.'
                    );
                    continue;
                }

                $ofertaProgramaDestino = $ofertaProgramaPorFicha->get($normalizarFicha($numeroFicha));

                if (!$ofertaProgramaDestino) {
                    $failed++;
                    $registrarResultado(
                        $resultadosPorFila,
                        $i + 1,
                        $baseData,
                        false,
                        "Fallo: No existe relación oferta-programa para la ficha ($numeroFicha) en la oferta seleccionada."
                    );
                    continue;
                }
                
                // Validar estado
                $estadoEnum = EstadoPreinscrito::tryFromInput($estado);
                if (!$estadoEnum && $estado !== '') {
                    $failed++;
                    $registrarResultado(
                        $resultadosPorFila,
                        $i + 1,
                        $baseData,
                        false,
                        "Fallo: Estado inválido ($estado)."
                    );
                    continue;
                }

                $estado = $estadoEnum?->value ?? $estadoPorDefecto;
                $baseData['estado'] = $estado;
                
                // Verificar si ya existe en el mismo programa/oferta
                $existeMismoPrograma = Preinscrito::where('documento', $documento)
                    ->where('oferta_programa_id', $ofertaProgramaDestino->id)
                    ->exists();

                if ($existeMismoPrograma) {
                    $failed++;
                    $registrarResultado(
                        $resultadosPorFila,
                        $i + 1,
                        $baseData,
                        false,
                        'Fallo: El preinscrito ya se encuentra dentro del mismo programa en la base de datos.'
                    );
                    continue;
                }

                // Verificar si existe en otro programa
                $existeOtroPrograma = Preinscrito::where('documento', $documento)
                    ->where('oferta_programa_id', '!=', $ofertaProgramaDestino->id)
                    ->first();

                if ($existeOtroPrograma) {
                    $failed++;
                    $registrarResultado(
                        $resultadosPorFila,
                        $i + 1,
                        $baseData,
                        false,
                        'Fallo: El preinscrito ya está registrado en la base de datos, pero en un programa distinto al señalado en la importación.'
                    );
                    continue;
                }
                
                // Crear preinscrito
                try {
                    Preinscrito::create([
                        'nombre' => $nombre,
                        'apellido' => $apellido,
                        'tipo_documento' => $tipoDocumento,
                        'documento' => $documento,
                        'correo' => $correo,
                        'oferta_programa_id' => $ofertaProgramaDestino->id,
                        'oferta_id' => $ofertaDestino->id,
                        'estado' => $estado,
                    ]);
                    $imported++;

                    $registrarResultado(
                        $resultadosPorFila,
                        $i + 1,
                        $baseData,
                        true,
                        'Éxito: Preinscrito importado correctamente.'
                    );
                } catch (\Exception $e) {
                    $failed++;
                    $registrarResultado(
                        $resultadosPorFila,
                        $i + 1,
                        $baseData,
                        false,
                        'Fallo: Error al guardar - ' . $e->getMessage()
                    );
                }
            }

            $auditSpreadsheet = new Spreadsheet();
            $auditSheet = $auditSpreadsheet->getActiveSheet();

            $auditSheet->getColumnDimension('A')->setWidth(20);
            $auditSheet->getColumnDimension('B')->setWidth(20);
            $auditSheet->getColumnDimension('C')->setWidth(15);
            $auditSheet->getColumnDimension('D')->setWidth(15);
            $auditSheet->getColumnDimension('E')->setWidth(30);
            $auditSheet->getColumnDimension('F')->setWidth(30);
            $auditSheet->getColumnDimension('G')->setWidth(15);
            $auditSheet->getColumnDimension('H')->setWidth(18);
            $auditSheet->getColumnDimension('I')->setWidth(65);
            $auditSheet->getColumnDimension('J')->setWidth(40);

            $logoPath = public_path('logo-sena.png');
            if (file_exists($logoPath)) {
                $drawing = new Drawing();
                $drawing->setName('Logo SENA');
                $drawing->setDescription('Logo del SENA');
                $drawing->setPath($logoPath);
                $drawing->setCoordinates('A1');
                $drawing->setHeight(50);
                $drawing->setOffsetX(5);
                $drawing->setOffsetY(5);
                $drawing->setWorksheet($auditSheet);
            }

            $auditSheet->mergeCells('B1:I1');
            $headerCell = $auditSheet->getCell('B1');
            $headerCell->setValue('CENTRO AGROEMPRESARIAL Y TURÍSTICO DE LOS ANDES - SENA');
            $headerCell->getStyle()->getFont()->setSize(14)->setBold(true)->setColor(new Color('FFFFFF'));
            $headerCell->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('39A900');
            $headerCell->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
            $auditSheet->getRowDimension('1')->setRowHeight(30);

            $auditSheet->mergeCells('A2:I2');
            $subtitleCell = $auditSheet->getCell('A2');
            $subtitleCell->setValue('RESULTADO DE IMPORTACIÓN DE PREINSCRITOS - Oferta: ' . ($ofertaDestino->nombre ?? ('#' . $ofertaDestino->id)) . ' - ' . now()->format('d/m/Y H:i'));
            $subtitleCell->getStyle()->getFont()->setSize(10)->setBold(true)->setColor(new Color('00304D'));
            $subtitleCell->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
            $subtitleCell->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E8F5E9');
            $auditSheet->getRowDimension('2')->setRowHeight(22);

            $auditSheet->mergeCells('A3:I3');
            $summaryCell = $auditSheet->getCell('A3');
            $summaryCell->setValue("Resumen: $imported exitosos / $failed fallidos / " . count($resultadosPorFila) . ' procesados');
            $summaryCell->getStyle()->getFont()->setSize(10)->setBold(true)->setColor(new Color('00304D'));
            $summaryCell->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setVertical(Alignment::VERTICAL_CENTER);
            $summaryCell->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F2F7F2');
            $auditSheet->getRowDimension('3')->setRowHeight(20);

            $headers = ['Nombres', 'Apellidos', 'Tipo Documento', 'Documento', 'Correo Electrónico', 'Programa', 'Número Ficha', 'Estado', 'Resultado Importación'];
            for ($i = 0; $i < count($headers); $i++) {
                $column = chr(65 + $i);
                $cell = $auditSheet->getCell($column . '4');
                $cell->setValue($headers[$i]);

                $cell->getStyle()->getFont()->setBold(true)->setSize(11)->setColor(new Color('FFFFFF'));
                $cell->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('00304D');
                $cell->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);
                $cell->getStyle()->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('E0E0E0'));
            }
            $auditSheet->getRowDimension('4')->setRowHeight(24);

            $rowAudit = 5;
            foreach ($resultadosPorFila as $resultado) {
                $data = $resultado['data'];

                $auditSheet->setCellValue('A' . $rowAudit, $data['nombre']);
                $auditSheet->setCellValue('B' . $rowAudit, $data['apellido']);
                $auditSheet->setCellValue('C' . $rowAudit, $data['tipo_documento']);
                $auditSheet->setCellValue('D' . $rowAudit, $data['documento']);
                $auditSheet->setCellValue('E' . $rowAudit, $data['correo']);
                $auditSheet->setCellValue('F' . $rowAudit, $data['programa']);
                $auditSheet->setCellValue('G' . $rowAudit, $data['numero_ficha']);
                $auditSheet->setCellValue('H' . $rowAudit, $data['estado']);
                $auditSheet->setCellValue('I' . $rowAudit, $resultado['message']);

                for ($col = 'A'; $col <= 'I'; $col++) {
                    $cell = $auditSheet->getCell($col . $rowAudit);
                    $cell->getStyle()->getFont()->setSize(10)->setColor(new Color('333333'));
                    $cell->getStyle()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);
                    $cell->getStyle()->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color('E0E0E0'));
                }

                if ($resultado['success']) {
                    $auditSheet->getStyle('I' . $rowAudit)
                        ->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setRGB('C6EFCE');
                    $auditSheet->getStyle('I' . $rowAudit)->getFont()->setColor(new Color('006100'))->setBold(true);
                } else {
                    $auditSheet->getStyle('I' . $rowAudit)
                        ->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setRGB('FFC7CE');
                    $auditSheet->getStyle('I' . $rowAudit)->getFont()->setColor(new Color('9C0006'))->setBold(true);
                }

                $auditSheet->getRowDimension((string) $rowAudit)->setRowHeight(22);
                $rowAudit++;
            }

            $instructionsTitleRow = max($rowAudit + 1, 9);
            $auditSheet->setCellValue('J' . $instructionsTitleRow, 'INSTRUCCIONES DE FISCALIZACIÓN');
            $auditSheet->getStyle('J' . $instructionsTitleRow)->getFont()->setBold(true)->setSize(11)->setColor(new Color('39A900'));

            $instructions = [
                '• Columna I en verde: registro importado correctamente.',
                '• Columna I en rojo: registro no importado, revise motivo en el mensaje.',
                '• Revise y corrija únicamente las filas en rojo para una nueva importación.',
                '• Este archivo conserva la estructura de la plantilla base para control y trazabilidad.',
            ];

            $instructionRow = $instructionsTitleRow + 1;
            foreach ($instructions as $instruction) {
                $auditSheet->setCellValue('J' . $instructionRow, $instruction);
                $auditSheet->getStyle('J' . $instructionRow)->getFont()->setSize(9)->setColor(new Color('333333'));
                $auditSheet->getStyle('J' . $instructionRow)->getAlignment()->setWrapText(true);
                $instructionRow++;
            }

            $tempDir = storage_path('app/temp');
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            $safeOfferName = Str::slug((string) ($ofertaDestino->nombre ?? ('oferta-' . $ofertaDestino->id)));
            $filename = 'Resultado_Importacion_Preinscritos_' . $safeOfferName . '_' . now()->format('Ymd_His') . '.xlsx';
            $filePath = $tempDir . DIRECTORY_SEPARATOR . $filename;

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($auditSpreadsheet, 'Xlsx');
            $writer->save($filePath);

            return response()->download($filePath, $filename)->deleteFileAfterSend(true);
                
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.preinscritos.index')
                ->with('error', 'Error al procesar el archivo: ' . $e->getMessage());
        }
    }
}
