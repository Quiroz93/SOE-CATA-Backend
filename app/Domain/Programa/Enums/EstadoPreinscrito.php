<?php

declare(strict_types=1);

namespace App\Domain\Programa\Enums;

enum EstadoPreinscrito: string
{
    case PENDIENTE = 'pendiente';
    case NOVEDAD = 'novedad';
    case PREINSCRITO = 'preinscrito';
    case INSCRITO = 'inscrito';
    case CANCELADO = 'cancelado';
    case CONVOCADO_MATRICULA = 'convocado_matricula';
    case MATRICULADO = 'matriculado';
    case NO_ADMITIDO = 'no_admitido';
    case RECHAZADO = 'rechazado';
}
