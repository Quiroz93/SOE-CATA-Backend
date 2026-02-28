<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfertaPrograma extends Model
{
    use HasFactory;
    protected $table = 'oferta_programa';

    use HasFactory, SoftDeletes;
    protected $fillable = [
        'oferta_id',
        'programa_id',
        'instructor_id',
        'cupos',
        'estado',
        'version',
    ];

    protected $casts = [
        'estado' => 'boolean',
        'cupos' => 'integer',
        'version' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function oferta()
    {
        return $this->belongsTo(Oferta::class);
    }

    public function programa()
    {
        return $this->belongsTo(Programa::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function preinscritos()
    {
        return $this->hasMany(Preinscrito::class);
    }

    // Scope para registros activos
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }
}
