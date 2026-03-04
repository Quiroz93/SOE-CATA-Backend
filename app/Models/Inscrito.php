<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscrito extends Model
{
    use HasFactory;

    protected $fillable = [
        'preinscrito_id',
        'oferta_id',
        'programa_id',
        'estado',
    ];

    public function preinscrito()
    {
        return $this->belongsTo(Preinscrito::class);
    }

    public function oferta()
    {
        return $this->belongsTo(Oferta::class);
    }

    public function programa()
    {
        return $this->belongsTo(Programa::class);
    }
}
