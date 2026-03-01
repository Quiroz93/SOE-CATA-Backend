<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ReporteController extends Controller
{
    public function index()
    {
        return view('admin.reportes.index');
    }
}
