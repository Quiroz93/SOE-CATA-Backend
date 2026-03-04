<?php

namespace App\Application\Statistics;

use App\Application\Statistics\Contracts\ExcelReportAnalyzer;

class DashboardReportAnalyzerFactory
{
    public const KIND_GENERAL = 'general_inscripciones';
    public const KIND_INDIVIDUAL = 'individual_ficha';

    public function make(string $reportKind): ExcelReportAnalyzer
    {
        return match ($reportKind) {
            self::KIND_INDIVIDUAL => new AnalyzeExcelIndividualByFicha(),
            self::KIND_GENERAL => new AnalyzeExcelByProgram(),
            default => throw new \InvalidArgumentException('Tipo de reporte no soportado.'),
        };
    }
}
