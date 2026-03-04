<?php

namespace App\Application\Statistics;

/**
 * Exportar datos consolidados de fichas a PDF
 * Genera un PDF con formato profesional con los datos consolidados
 */
class ExportConsolidatedFichasPDF
{
    /**
     * Generar archivo PDF con datos consolidados
     * Retorna contenido HTML que será convertido a PDF en el controlador
     */
    public function generateHTML(array $data): string
    {
        $totales = $data['totales'] ?? [];
        $estadosGlobales = $data['estados_globales'] ?? [];
        $fichas = $data['fichas'] ?? [];

        $html = $this->getHTMLTemplate();

        // Reemplazar datos
        $html = str_replace(
            ['{{titulo}}', '{{fecha}}', '{{total_fichas}}', '{{total_aprendices}}', '{{total_estados}}'],
            [
                'CONSOLIDADO DE REPORTES INDIVIDUALES POR FICHA',
                now()->format('Y-m-d H:i:s'),
                $totales['fichas'] ?? 0,
                $totales['aprendices'] ?? 0,
                $totales['estados'] ?? 0,
            ],
            $html
        );

        // Tabla de estados consolidados
        $estadosHTML = $this->generateEstadosTable($estadosGlobales);
        $html = str_replace('{{tabla_estados}}', $estadosHTML, $html);

        // Tabla detallada de fichas
        $fichasHTML = $this->generateFichasTable($fichas);
        $html = str_replace('{{tabla_fichas}}', $fichasHTML, $html);

        return $html;
    }

    /**
     * Template HTML base del PDF
     */
    private function getHTMLTemplate(): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consolidado de Fichas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            color: #333;
            background: white;
            line-height: 1.6;
        }

        .header {
            background: linear-gradient(135deg, #39A900 0%, #2d8600 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-bottom: 3px solid #1b5e20;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .header p {
            font-size: 12px;
            opacity: 0.9;
        }

        .metadata {
            background: #f5f5f5;
            padding: 20px;
            margin: 20px;
            border-radius: 8px;
            border-left: 5px solid #39A900;
        }

        .metadata-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #ddd;
        }

        .metadata-row:last-child {
            border-bottom: none;
        }

        .metadata-label {
            font-weight: 600;
            color: #1b5e20;
        }

        .metadata-value {
            color: #333;
            font-weight: 500;
        }

        .section-title {
            background: #00304D;
            color: white;
            padding: 12px 20px;
            margin: 30px 20px 15px 20px;
            font-size: 14px;
            font-weight: 700;
            border-radius: 4px;
        }

        table {
            width: 90%;
            margin: 0 auto 30px;
            border-collapse: collapse;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        thead {
            background: #00304D;
            color: white;
        }

        th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            border: 1px solid #ddd;
        }

        td {
            padding: 10px 12px;
            border: 1px solid #ddd;
        }

        tbody tr {
            transition: background-color 0.2s;
        }

        tbody tr:nth-child(even) {
            background: #f9f9f9;
        }

        tbody tr:hover {
            background: #f0f8ff;
        }

        .numero {
            text-align: right;
            font-weight: 500;
        }

        .ficha-codigo {
            background: #e8f5e9;
            font-weight: 700;
            color: #1b5e20;
            border-left: 5px solid #39A900;
        }

        .ficha-header {
            background: #e8f5e9;
            font-weight: 600;
            padding: 8px 12px;
            border: 1px solid #39A900;
            color: #1b5e20;
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: #999;
            font-size: 10px;
            border-top: 1px solid #ddd;
            margin-top: 40px;
        }

        @media print {
            body {
                background: white;
            }
            
            .header {
                page-break-after: avoid;
            }

            table {
                page-break-inside: avoid;
            }
        }

        .estado-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 500;
            background: #e3f2fd;
            color: #0d47a1;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{titulo}}</h1>
        <p>Generado: {{fecha}}</p>
    </div>

    <div class="metadata">
        <div class="metadata-row">
            <span class="metadata-label">Total de Fichas:</span>
            <span class="metadata-value">{{total_fichas}}</span>
        </div>
        <div class="metadata-row">
            <span class="metadata-label">Total de Aprendices:</span>
            <span class="metadata-value">{{total_aprendices}}</span>
        </div>
        <div class="metadata-row">
            <span class="metadata-label">Estados Detectados:</span>
            <span class="metadata-value">{{total_estados}}</span>
        </div>
    </div>

    <div class="section-title">📊 Estados Consolidados</div>
    {{tabla_estados}}

    <div class="section-title">📋 Detalle de Aprendices por Ficha</div>
    {{tabla_fichas}}

    <div class="footer">
        <p>Documento generado automáticamente por el Sistema de Consolidación de Fichas</p>
        <p>© 2026 SENA - Todos los derechos reservados</p>
    </div>
</body>
</html>
HTML;
    }

    /**
     * Generar tabla HTML de estados consolidados
     */
    private function generateEstadosTable(array $estados): string
    {
        $sorted = $estados;
        arsort($sorted);

        $html = '<table><thead><tr><th style="width: 70%;">Estado</th><th style="width: 30%;" class="numero">Total Aprendices</th></tr></thead><tbody>';

        foreach ($sorted as $estado => $total) {
            $html .= '<tr><td>' . htmlspecialchars($estado) . '</td><td class="numero">' . (int) $total . '</td></tr>';
        }

        $html .= '</tbody></table>';

        return $html;
    }

    /**
     * Generar tabla HTML de fichas con aprendices
     */
    private function generateFichasTable(array $fichas): string
    {
        $html = '<table><thead><tr><th style="width: 15%;">Código Ficha</th><th style="width: 20%;">Identificación</th><th style="width: 40%;">Nombre</th><th style="width: 25%;">Estado</th></tr></thead><tbody>';

        foreach ($fichas as $codigoFicha => $fichaData) {
            $aprendices = $fichaData['aprendices'] ?? [];

            if (empty($aprendices)) {
                continue;
            }

            foreach ($aprendices as $idx => $aprendiz) {
                $html .= '<tr>';

                // Código ficha solo en primera fila
                if ($idx === 0) {
                    $html .= '<td class="ficha-codigo" rowspan="' . count($aprendices) . '">' . htmlspecialchars($codigoFicha) . '</td>';
                }

                $html .= '<td>' . htmlspecialchars($aprendiz['identificacion'] ?? '') . '</td>';
                $html .= '<td>' . htmlspecialchars($aprendiz['nombre'] ?? '') . '</td>';
                $html .= '<td><span class="estado-badge">' . htmlspecialchars($aprendiz['estado'] ?? '') . '</span></td>';
                $html .= '</tr>';
            }
        }

        $html .= '</tbody></table>';

        return $html;
    }
}
