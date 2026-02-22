<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Instructor;

class InstructorController extends Controller
{
    public function index()
    {
        return view('public.instructores.index');
    }
    public function show(Instructor $instructor)
    {
        return view('public.instructores.show');
    }
}
