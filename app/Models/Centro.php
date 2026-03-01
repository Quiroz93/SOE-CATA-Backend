<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Centro extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre', 'codigo', 'direccion', 'telefono', 'email', 'estado'
    ];

    public function scopePublished($query)
    {
        return $query->where('estado', 'activo');
    }
}
