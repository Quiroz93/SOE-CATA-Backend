<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Instructor extends Model
{
    use HasFactory;
    protected $table = 'instructores';
    protected $fillable = [
        'nombre', 'perfil_descriptivo', 'experiencia', 'activo'
    ];
    protected $casts = [
        'activo' => 'boolean',
    ];

    public function programas()
    {
        return $this->belongsToMany(Programa::class, 'instructor_programa');
    }
}
