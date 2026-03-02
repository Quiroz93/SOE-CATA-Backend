<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Preinscrito;
use App\Models\OfertaPrograma;
use App\Models\Programa;
use Illuminate\Http\Request;
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
        
        // Estados válidos
        $estados = ['pendiente', 'aceptado', 'rechazado'];
        
        // Crear nuevo spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Configurar ancho de columnas
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(35);
        $sheet->getColumnDimension('E')->setWidth(18);
        
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
        // Fusionar celdas para encabezado (B1:E1)
        $sheet->mergeCells('B1:E1');
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
        $sheet->mergeCells('A2:E2');
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
        $headers = ['Nombre Completo', 'Cédula', 'Correo Electrónico', 'Programa', 'Estado'];
        
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
        $programaEjemplo = $programas[0] ?? 'Técnico en Agronomía';
        
        $exampleData = [
            ['Juan Carlos Pérez López', '1234567890', 'juan.perez@example.com', $programaEjemplo, 'pendiente'],
            ['María García Rodriguez', '0987654321', 'maria.garcia@example.com', $programaEjemplo, 'pendiente'],
            ['Pedro López Martinez', '5555555555', 'pedro.lopez@example.com', $programaEjemplo, 'pendiente'],
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
        
        // Escribir programas en la hoja de validación (columna A)
        $row = 1;
        foreach ($programas as $programa) {
            $validationSheet->setCellValue('A' . $row, $programa);
            $row++;
        }
        
        // Escribir estados en la hoja de validación (columna B)
        $row = 1;
        foreach ($estados as $estado) {
            $validationSheet->setCellValue('B' . $row, $estado);
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
        $programasCount = count($programas);
        $estadosCount = count($estados);
        
        // Aplicar validación a las primeras 100 filas (desde fila 5 hasta 104)
        // Columna D: Programas
        for ($i = 5; $i <= 104; $i++) {
            $validation = $sheet->getCell('D' . $i)->getDataValidation();
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
        
        // Columna E: Estado
        for ($i = 5; $i <= 104; $i++) {
            $validation = $sheet->getCell('E' . $i)->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Entrada inválida');
            $validation->setError('Por favor seleccione: pendiente, aceptado o rechazado.');
            $validation->setPromptTitle('Estado');
            $validation->setPrompt('Seleccione el estado del preinscrito.');
            $validation->setFormula1('Datos_Validacion!$B$1:$B$' . $estadosCount);
        }
        
        // ============================================
        // INSTRUCCIONES
        // ============================================
        $sheet->mergeCells('A9:E9');
        $instructionsTitle = $sheet->getCell('A9');
        $instructionsTitle->setValue('📋 INSTRUCCIONES DE USO');
        
        $instructionsTitle->getStyle()
            ->getFont()
            ->setBold(true)
            ->setSize(11)
            ->setColor(new Color('39A900'));
        
        $sheet->getRowDimension('9')->setRowHeight(18);
        
        $programasDisponibles = !empty($programas) ? implode(', ', array_slice($programas, 0, 3)) . '...' : 'Consulte la lista desplegable';
        
        $instructions = [
            '• Nombre Completo: Ingrese el nombre y apellido completo del preinscrito',
            '• Cédula: Ingrese el número de cédula o documento de identidad (sin puntos ni espacios)',
            '• Correo Electrónico: Ingrese un correo electrónico válido y activo',
            '• Programa: SELECCIONE de la lista desplegable. Programas disponibles: ' . $programasDisponibles,
            '• Estado: SELECCIONE de la lista desplegable (pendiente, aceptado o rechazado)',
            '',
            '⚠️ IMPORTANTE: Los campos Nombre, Cédula y Correo son OBLIGATORIOS',
            '⚠️ No modifique los encabezados de las columnas',
            '⚠️ Use las listas desplegables en las columnas Programa y Estado',
        ];
        
        $instructionRow = 10;
        foreach ($instructions as $instruction) {
            $sheet->mergeCells('A' . $instructionRow . ':E' . $instructionRow);
            $cell = $sheet->getCell('A' . $instructionRow);
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
        return view('admin.preinscritos.import');
    }
    
    /**
     * Procesar importación desde Excel
     */
    public function handleImport(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:5120', // 5MB máximo
        ]);
        
        try {
            $file = $request->file('excel_file');
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            
            $rows = $sheet->toArray();
            $imported = 0;
            $errors = [];
            
            // Saltar encabezados (primeras 4 filas son encabezado)
            for ($i = 5; $i < count($rows); $i++) {
                $row = $rows[$i];
                
                // Validar que la fila tenga datos
                if (empty(trim($row[0] ?? ''))) {
                    continue; // Saltar filas vacías
                }
                
                $nombre = trim($row[0] ?? '');
                $documento = trim($row[1] ?? '');
                $correo = trim($row[2] ?? '');
                $programaNombre = trim($row[3] ?? '');
                $estado = trim($row[4] ?? 'pendiente');
                
                // Validaciones básicas
                if (!$nombre || !$documento || !$correo) {
                    $errors[] = "Fila " . ($i + 1) . ": Datos incompletos (Nombre, Cédula y Correo son obligatorios)";
                    continue;
                }
                
                if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Fila " . ($i + 1) . ": Correo inválido ($correo)";
                    continue;
                }
                
                // Buscar programa por nombre
                $ofertaProgrma = OfertaPrograma::whereHas('programa', function($q) use ($programaNombre) {
                    $q->where('nombre', 'like', '%' . $programaNombre . '%');
                })->first();
                
                if (!$ofertaProgrma) {
                    $errors[] = "Fila " . ($i + 1) . ": Programa no encontrado ($programaNombre)";
                    continue;
                }
                
                // Validar estado
                $estadoValido = ['pendiente', 'aceptado', 'rechazado'];
                if (!in_array($estado, $estadoValido)) {
                    $estado = 'pendiente';
                }
                
                // Verificar si ya existe
                $existe = Preinscrito::where('documento', $documento)
                    ->where('correo', $correo)
                    ->exists();
                
                if ($existe) {
                    $errors[] = "Fila " . ($i + 1) . ": Preinscrito con este documento y correo ya existe";
                    continue;
                }
                
                // Crear preinscrito
                try {
                    Preinscrito::create([
                        'nombre' => $nombre,
                        'documento' => $documento,
                        'correo' => $correo,
                        'oferta_programa_id' => $ofertaProgrma->id,
                        'oferta_id' => $ofertaProgrma->oferta_id,
                        'estado' => $estado,
                    ]);
                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Fila " . ($i + 1) . ": Error al guardar - " . $e->getMessage();
                }
            }
            
            $message = "Se importaron $imported preinscritos correctamente.";
            if (!empty($errors)) {
                $message .= " Se encontraron " . count($errors) . " errores.";
            }
            
            return redirect()
                ->route('admin.preinscritos.index')
                ->with('success', $message)
                ->with('errors', $errors);
                
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.preinscritos.index')
                ->with('error', 'Error al procesar el archivo: ' . $e->getMessage());
        }
    }
}
