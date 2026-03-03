<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Domain\Programa\Enums\EstadoPrograma;

/**
 * Modelo que representa un Programa académico.
 */
class Programa extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Scope para filtrar programas publicados.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('estado', EstadoPrograma::PUBLICADO->value);
    }

    public function scopePublicado(Builder $query): Builder
    {
        return $this->scopePublished($query);
    }

    protected $fillable = [
        'nombre',
        'slug',
        'ficha',
        'nivel',
        'descripcion',
        'estado',
        'modalidad',
        'municipio',
        'duracion',
        'imagen_portada',
        'nivel_formacion_id',
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

    public function nivelFormacion()
    {
        return $this->belongsTo(NivelFormacion::class, 'nivel_formacion_id');
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

    public function redesFormacion()
    {
        return $this->belongsToMany(RedFormacion::class, 'programa_red_formacion', 'programa_id', 'red_formacion_id')
            ->wherePivotNull('deleted_at')
            ->withPivot([
                'id',
                'estado',
                'fecha_asignacion',
                'fecha_desasignacion',
                'usuario_asigno_id',
                'usuario_modifico_id',
                'observaciones',
                'deleted_at',
            ])
            ->withTimestamps();
    }

    public function redesFormacionActivas()
    {
        return $this->hasMany(ProgramaRedFormacion::class)
            ->where('estado', 'activo');
    }
}
