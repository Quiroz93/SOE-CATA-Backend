<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preinscrito extends Model
{
    use HasFactory;

    protected $fillable = [
        'oferta_programa_id',
        'nombre',
        'documento',
        'correo',
        'estado',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relación solo con OfertaPrograma

    public function ofertaPrograma()
    {
        return $this->belongsTo(OfertaPrograma::class);
    }
}

