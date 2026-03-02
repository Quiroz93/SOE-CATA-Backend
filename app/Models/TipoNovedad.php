<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoNovedad extends Model
{
    use HasFactory;

    protected $table = 'tipos_novedad';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación: Un tipo de novedad tiene muchas novedades de preinscritos
     */
    public function novedadesPreinscritos()
    {
        return $this->hasMany(NovedadPreinscrito::class);
    }
}
