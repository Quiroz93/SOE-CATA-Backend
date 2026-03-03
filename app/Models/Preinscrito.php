<?php

namespace App\Models;

use App\Domain\Programa\Enums\EstadoPreinscrito;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preinscrito extends Model
{
    use HasFactory;

    protected $fillable = [
        'oferta_id',
        'oferta_programa_id',
        'nombre',
        'apellido',
        'tipo_documento',
        'documento',
        'correo',
        'estado',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'estado' => EstadoPreinscrito::class,
    ];

    /**
     * Appends: atributos calculados que se incluyen en arrays
     */
    protected $appends = ['nombre_completo'];

    /**
     * Obtener nombre completo
     */
    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombre} {$this->apellido}");
    }

    /**
     * Relación: Un preinscrito pertenece a una oferta de programa
     */
    public function ofertaPrograma()
    {
        return $this->belongsTo(OfertaPrograma::class);
    }

    /**
     * Relación: Un preinscrito tiene muchas novedades
     */
    public function novedades()
    {
        return $this->hasMany(NovedadPreinscrito::class);
    }

    public function getEstadoValorAttribute(): ?string
    {
        if ($this->estado instanceof EstadoPreinscrito) {
            return $this->estado->value;
        }

        return $this->estado;
    }

    public function getEstadoLabelAttribute(): ?string
    {
        if ($this->estado instanceof EstadoPreinscrito) {
            return $this->estado->label();
        }

        if ($this->estado === null) {
            return null;
        }

        return ucfirst(strtolower(str_replace('_', ' ', (string) $this->estado)));
    }

    public function getEstadoCssClassAttribute(): ?string
    {
        if ($this->estado instanceof EstadoPreinscrito) {
            return $this->estado->cssClass();
        }

        if ($this->estado === null) {
            return null;
        }

        return strtolower(str_replace(' ', '_', trim((string) $this->estado)));
    }
}

