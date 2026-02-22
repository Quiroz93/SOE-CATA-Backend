<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Centro extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre', 'codigo', 'estado'
    ];

    public function scopePublished($query)
    {
        return $query->where('estado', 'publicado');
    }
}
