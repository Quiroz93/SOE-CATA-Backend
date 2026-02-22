<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class RedFormacion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'red_formacion';

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];

    protected $dates = ['deleted_at'];

    public function programaRelaciones()
    {
        return $this->hasMany(ProgramaRedFormacion::class);
    }
}