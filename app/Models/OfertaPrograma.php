<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class OfertaPrograma extends Model
{
    use HasFactory;
    protected $table = 'oferta_programa';

    protected $fillable = [
        'oferta_id',
        'programa_id',
        'cupos',
        'estado',
        'fecha_inicio',
        'fecha_fin'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'cupos' => 'integer',
    ];

    public function oferta()
    {
        return $this->belongsTo(Oferta::class);
    }

    public function programa()
    {
        return $this->belongsTo(Programa::class);
    }

    public function preinscritos()
    {
        return $this->hasMany(Preinscrito::class);
    }
}
