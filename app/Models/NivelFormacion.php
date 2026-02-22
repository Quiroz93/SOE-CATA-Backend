<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class NivelFormacion extends Model
{
    use HasFactory;
    protected $table = 'niveles_formacion';
    protected $fillable = ['nombre', 'descripcion'];
    protected $casts = [
        'descripcion' => 'string',
    ];

    public function programaDetalles()
    {
        return $this->hasMany(ProgramaDetalle::class, 'nivel_formacion_id');
    }
}
