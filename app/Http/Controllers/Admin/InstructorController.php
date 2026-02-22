<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use Illuminate\Http\Request;

class InstructorController extends Controller
{
    public function index() { return view('admin.instructores.index'); }
    public function show(Instructor $instructor) { return view('admin.instructores.show'); }
    public function create() { return view('admin.instructores.create'); }
    public function store(Request $request) { return view('admin.instructores.index'); }
    public function edit(Instructor $instructor) { return view('admin.instructores.edit'); }
    public function update(Request $request, Instructor $instructor) { return view('admin.instructores.index'); }
    public function destroy(Instructor $instructor) { return view('admin.instructores.index'); }
}
