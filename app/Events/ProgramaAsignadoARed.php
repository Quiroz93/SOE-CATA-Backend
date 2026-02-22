<?php

namespace App\Events;

use App\Models\ProgramaRedFormacion;
use Illuminate\Queue\SerializesModels;

class ProgramaAsignadoARed
{
    use SerializesModels;

    public $relacion;

    public function __construct(ProgramaRedFormacion $relacion)
    {
        $this->relacion = $relacion;
    }
}

class ProgramaDesasignadoDeRed
{
    use SerializesModels;

    public $relacion;

    public function __construct(ProgramaRedFormacion $relacion)
    {
        $this->relacion = $relacion;
    }
}
