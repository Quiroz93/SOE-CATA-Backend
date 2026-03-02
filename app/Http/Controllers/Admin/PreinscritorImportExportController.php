<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Preinscrito;
use App\Models\OfertaPrograma;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class PreinscritorImportExportController extends Controller
{
    /**
     * Descargar plantilla Excel estándar para recopilación de preinscritos
     */
    public function downloadTemplate()
    {
        // Crear nuevo spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Configurar ancho de columnas
        $sheet->getColumnDimension('A')->setWidth(18);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(18);
        
        // ENCABEZADO INSTITUCIONAL
        // Fusionar celdas para encabezado (A1:E1)
        $sheet->mergeCells('A1:E1');
        $headerCell = $sheet->getCell('A1');
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
        
        $sheet->getRowDimension('1')->setRowHeight(25);
        
        // Subtítulo
        $sheet->mergeCells('A2:E2');
        $subtitleCell = $sheet->getCell('A2');
        $subtitleCell->setValue('PLANTILLA ESTÁNDAR DE RECOPILACIÓN DE PREINSCRITOS');
        
        $subtitleCell->getStyle()
            ->getFont()
            ->setSize(11)
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
            ->setRGB('F0F0F0'); // Gris claro
        
        $sheet->getRowDimension('2')->setRowHeight(20);
        
        // Espacio en blanco
        $sheet->getRowDimension('3')->setRowHeight(8);
        
        // ENCABEZADOS DE TABLA
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
        
        $sheet->getRowDimension('4')->setRowHeight(22);
        
        // DATOS DE EJEMPLO
        $exampleData = [
            ['Juan Carlos Pérez López', '1234567890', 'juan.perez@example.com', 'Técnico en Agronomía', 'pendiente'],
            ['María García Rodriguez', '0987654321', 'maria.garcia@example.com', 'Técnico en Turismo', 'pendiente'],
            ['Pedro López Martinez', '5555555555', 'pedro.lopez@example.com', 'Técnico Agrícola', 'pendiente'],
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
                    ->setColor(new Color('666666'));
                
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
            
            $sheet->getRowDimension($row)->setRowHeight(18);
            $row++;
        }
        
        // INSTRUCCIONES
        $sheet->mergeCells('A9:E9');
        $instructionsTitle = $sheet->getCell('A9');
        $instructionsTitle->setValue('INSTRUCCIONES DE USO');
        
        $instructionsTitle->getStyle()
            ->getFont()
            ->setBold(true)
            ->setSize(10)
            ->setColor(new Color('39A900'));
        
        $sheet->getRowDimension('9')->setRowHeight(16);
        
        $instructions = [
            '• Nombre Completo: Ingrese el nombre y apellido del preinscrito',
            '• Cédula: Ingrese el número de cédula o documento de identidad',
            '• Correo Electrónico: Ingrese un correo válido',
            '• Programa: Seleccione de la lista disponible: Técnico en Agronomía, Técnico en Turismo, Técnico Agrícola',
            '• Estado: Use "pendiente", "aceptado" o "rechazado"',
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
