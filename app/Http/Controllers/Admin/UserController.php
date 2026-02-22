<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index() { return view('admin.users.index'); }
    public function show(User $user) { return view('admin.users.show'); }
    public function create() { return view('admin.users.create'); }
    public function store(Request $request) { return view('admin.users.index'); }
    public function edit(User $user) { return view('admin.users.edit'); }
    public function update(Request $request, User $user) { return view('admin.users.index'); }
    public function destroy(User $user) { return view('admin.users.index'); }
}
