<?php

namespace App\Application\Statistics\Contracts;

interface ExcelReportAnalyzer
{
    public function execute(string $filePath): array;
}
