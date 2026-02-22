<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Competencia extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nombre', 'codigo', 'descripcion'
    ];

    public function programas()
    {
        return $this->belongsToMany(Programa::class, 'competencia_programa');
    }
}
