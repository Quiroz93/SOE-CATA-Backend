<?php

declare(strict_types=1);

namespace App\Domain\Programa\Enums;

enum EstadoPrograma: string
{
    case BORRADOR = 'borrador';
    case PUBLICADO = 'publicado';
    case ARCHIVADO = 'archivado';
}
