<?php

declare(strict_types=1);

namespace App\Casts;

use App\Domain\Programa\Enums\EstadoPreinscrito;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

class EstadoPreinscritoCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): ?EstadoPreinscrito
    {
        if ($value === null || $value === '') {
            return null;
        }

        return EstadoPreinscrito::tryFromInput((string) $value);
    }

    public function set($model, string $key, $value, array $attributes): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof EstadoPreinscrito) {
            return $value->value;
        }

        $estado = EstadoPreinscrito::tryFromInput((string) $value);

        if (!$estado) {
            throw new InvalidArgumentException("El estado '{$value}' no es válido para " . EstadoPreinscrito::class);
        }

        return $estado->value;
    }
}
