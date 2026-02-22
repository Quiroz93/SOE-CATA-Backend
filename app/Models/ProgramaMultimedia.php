<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProgramaMultimedia extends Model
{
    use HasFactory;
    protected $table = 'programa_multimedia';
    protected $fillable = [
        'programa_id',
        'tipo',
        'url_externa',
        'ruta_archivo',
        'orden'
    ];
    protected $casts = [
        'orden' => 'integer',
    ];

    public function programa()
    {
        return $this->belongsTo(Programa::class);
    }

    // Manejo de archivos multimedia
    public function getUrlArchivoAttribute()
    {
        return $this->ruta_archivo ? Storage::url($this->ruta_archivo) : null;
    }
}
