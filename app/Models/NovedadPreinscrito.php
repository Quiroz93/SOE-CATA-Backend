<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NovedadPreinscrito extends Model
{
    use HasFactory;

    protected $table = 'novedades_preinscritos';

    protected $fillable = [
        'preinscrito_id',
        'tipo_novedad_id',
        'detalle',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación: Una novedad pertenece a un preinscrito
     */
    public function preinscrito()
    {
        return $this->belongsTo(Preinscrito::class);
    }

    /**
     * Relación: Una novedad pertenece a un tipo de novedad
     */
    public function tipoNovedad()
    {
        return $this->belongsTo(TipoNovedad::class);
    }
}
