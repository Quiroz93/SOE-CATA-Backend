<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProgramaTestimonio extends Model
{
    use HasFactory;
    protected $table = 'programa_testimonios';
    protected $fillable = [
        'programa_id',
        'nombre_usuario',
        'relato',
        'imagen',
        'destacado'
    ];
    protected $casts = [
        'destacado' => 'boolean',
    ];

    public function programa()
    {
        return $this->belongsTo(Programa::class);
    }

    // Manejo de imagen de testimonio
    public function getUrlImagenAttribute()
    {
        return $this->imagen ? Storage::url($this->imagen) : null;
    }
}
