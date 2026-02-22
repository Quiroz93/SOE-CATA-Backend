<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProgramaRedFormacion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'programa_red_formacion';

    protected $fillable = [
        'programa_id',
        'red_formacion_id',
        'estado',
        'fecha_asignacion',
        'fecha_desasignacion',
        'usuario_asigno_id',
        'usuario_modifico_id',
        'observaciones',
    ];

    protected $dates = [
        'fecha_asignacion',
        'fecha_desasignacion',
        'deleted_at',
    ];

    public function programa()
    {
        return $this->belongsTo(Programa::class);
    }

    public function redFormacion()
    {
        return $this->belongsTo(RedFormacion::class);
    }

    public function usuarioAsigno()
    {
        return $this->belongsTo(User::class, 'usuario_asigno_id');
    }

    public function usuarioModifico()
    {
        return $this->belongsTo(User::class, 'usuario_modifico_id');
    }
}
