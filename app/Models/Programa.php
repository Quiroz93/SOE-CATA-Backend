<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Domain\Programa\Enums\EstadoPrograma;

/**
 * Modelo que representa un Programa académico.
 */
class Programa extends Model
{
    /**
     * Scope para filtrar programas publicados.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('estado', EstadoPrograma::PUBLICADO->value);
    }
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombre',
        'ficha',
        'descripcion',
        'estado',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'estado' => EstadoPrograma::class,
    ];

    public function ofertas()
    {
        return $this->belongsToMany(Oferta::class, 'oferta_programa')
            ->withPivot(['cupos', 'estado', 'fecha_inicio', 'fecha_fin'])
            ->withTimestamps();
    }
        // Relaciones académicas
        public function detalle()
        {
            return $this->hasOne(ProgramaDetalle::class);
        }

        public function instructores()
        {
            return $this->belongsToMany(Instructor::class, 'instructor_programa');
        }

        public function multimedia()
        {
            return $this->hasMany(ProgramaMultimedia::class);
        }

        public function testimonios()
        {
            return $this->hasMany(ProgramaTestimonio::class);
        }
    public function redesFormacionRelaciones()
    {
        return $this->hasMany(ProgramaRedFormacion::class);
    }

    public function redesFormacionActivas()
    {
        return $this->hasMany(ProgramaRedFormacion::class)
            ->where('estado', 'activo'); // Si existe Enum para RedFormacion, refactorizar aquí también
    }
}
