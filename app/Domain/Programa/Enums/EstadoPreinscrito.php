<?php

declare(strict_types=1);

namespace App\Domain\Programa\Enums;

enum EstadoPreinscrito: string
{
    case PENDIENTE = 'pendiente';
    case NOVEDAD = 'novedad';
    case PREINSCRITO = 'preinscrito';
    case INSCRITO = 'inscrito';
    case RECHAZADO = 'rechazado';
}
