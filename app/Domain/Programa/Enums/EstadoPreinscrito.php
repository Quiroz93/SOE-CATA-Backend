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

    public static function values(): array
    {
        return array_map(static fn (self $estado): string => $estado->value, self::cases());
    }

    public static function tryFromInput(?string $value): ?self
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        $normalized = self::normalize($value);

        foreach (self::cases() as $case) {
            if (
                self::normalize($case->value) === $normalized
                || self::normalize($case->name) === $normalized
            ) {
                return $case;
            }
        }

        return null;
    }

    public static function acceptedValues(): array
    {
        $acceptedCaseNames = [
            'PREINSCRITO',
            'INSCRITO',
            'CONVOCADO_MATRICULA',
            'MATRICULADO',
        ];

        return array_values(array_map(
            static fn (self $case): string => $case->value,
            array_filter(
                self::cases(),
                static fn (self $case): bool => in_array($case->name, $acceptedCaseNames, true)
            )
        ));
    }

    public function label(): string
    {
        return str_replace('_', ' ', $this->value);
    }

    public function cssClass(): string
    {
        return self::normalize($this->value);
    }

    private static function normalize(string $value): string
    {
        return strtolower(str_replace(' ', '_', trim($value)));
    }
}
