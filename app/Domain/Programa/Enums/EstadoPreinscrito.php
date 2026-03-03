<?php

declare(strict_types=1);

namespace App\Domain\Programa\Enums;

enum EstadoPreinscrito: string
{
    case PENDIENTE = 'Pendiente';
    case NOVEDAD = 'Novedad';
    case PREINSCRITO = 'Preinscrito';
    case INSCRITO = 'Inscrito';
    case CANCELADO = 'Cancelado';
    case CONVOCADO_MATRICULA = 'Convocado_matricula';
    case MATRICULADO = 'Matriculado';
    case NO_ADMITIDO = 'No_admitido';
    case RECHAZADO = 'Rechazado';
}
