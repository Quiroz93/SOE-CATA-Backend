<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Noticia extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'contenido',
        'imagen',
        'fecha_publicacion',
        'publicada',
    ];

    protected $casts = [
        'publicada' => 'boolean',
        'fecha_publicacion' => 'date',
    ];

    public function scopePublicadas($query)
    {
        return $query->where('publicada', true);
    }

    public function scopeBorradores($query)
    {
        return $query->where('publicada', false);
    }
}
