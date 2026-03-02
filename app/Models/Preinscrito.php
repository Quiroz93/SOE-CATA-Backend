<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preinscrito extends Model
{
    use HasFactory;

    protected $fillable = [
        'oferta_id',
        'oferta_programa_id',
        'nombre',
        'tipo_documento',
        'documento',
        'correo',
        'estado',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

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
}

