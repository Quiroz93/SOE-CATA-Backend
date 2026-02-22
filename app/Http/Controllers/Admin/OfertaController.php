<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Oferta;
use Illuminate\Http\Request;

class OfertaController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Oferta::class, 'oferta');
    }

    public function index()
    {
        // ...
    }
    public function show(Oferta $oferta)
    {
        // ...
    }
    public function create()
    {
        // ...
    }
    public function store(Request $request)
    {
        // ...
    }
    public function edit(Oferta $oferta)
    {
        // ...
    }
    public function update(Request $request, Oferta $oferta)
    {
        // ...
    }
    public function destroy(Oferta $oferta)
    {
        // ...
    }
}
