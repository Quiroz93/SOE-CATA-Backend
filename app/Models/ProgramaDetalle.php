<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProgramaDetalle extends Model
{
    use HasFactory;
    protected $table = 'programa_detalles';
    protected $fillable = [
        'programa_id',
        'nivel_formacion_id',
        'numero_ficha',
        'requisitos',
        'duracion_meses',
        'titulo_otorgado',
        'codigo_snies',
        'registro_calidad',
        'observaciones'
    ];
    protected $casts = [
        'duracion_meses' => 'integer',
        'observaciones' => 'string',
    ];

    public function programa()
    {
        return $this->belongsTo(Programa::class);
    }

    public function nivelFormacion()
    {
        return $this->belongsTo(NivelFormacion::class);
    }
}
