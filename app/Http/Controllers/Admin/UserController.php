<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\UsuarioRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', User::class);
        $usuarios = User::orderBy('name')->paginate(15);
        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $this->authorize('create', User::class);
        return view('admin.usuarios.create');
    }

    public function store(UsuarioRequest $request)
    {
        $this->authorize('create', User::class);
        $data = $request->validated();
        $data['password'] = Hash::make($request->password);
        $usuario = User::create($data);
        return redirect()->route('admin.usuarios.show', $usuario)
            ->with('success', 'Usuario creado correctamente');
    }

    public function show(User $usuario)
    {
        $this->authorize('view', $usuario);
        return view('admin.usuarios.show', compact('usuario'));
    }

    public function edit(User $usuario)
    {
        $this->authorize('update', $usuario);
        return view('admin.usuarios.edit', compact('usuario'));
    }

    public function update(UsuarioRequest $request, User $usuario)
    {
        $this->authorize('update', $usuario);
        $data = $request->validated();
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $usuario->update($data);
        return redirect()->route('admin.usuarios.show', $usuario)
            ->with('success', 'Usuario actualizado correctamente');
    }

    public function destroy(User $usuario)
    {
        $this->authorize('delete', $usuario);
        $usuario->delete();
        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario eliminado correctamente');
    }
}
