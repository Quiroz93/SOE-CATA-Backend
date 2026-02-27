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
    public function scopePublished($query)
    {
        return $query->where('estado', 'publicado'); // Ajustar según Enum si aplica
    }
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombre',
        'slug',
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

    public function ofertaProgramas()
    {
        return $this->hasMany(OfertaPrograma::class);
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
