<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Noticia;
use App\Http\Requests\StoreNoticiaRequest;
use App\Http\Requests\UpdateNoticiaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NoticiaController extends Controller
{
    public function index(Request $request)
    {
        $query = Noticia::query();

        // Búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('contenido', 'like', "%{$search}%");
            });
        }

        // Filtro por estado
        if ($request->filled('publicada')) {
            $query->where('publicada', $request->publicada === '1');
        }

        $noticias = $query->orderByDesc('fecha_publicacion')->paginate(15)->withQueryString();

        return view('admin.noticias.index', compact('noticias'));
    }

    public function show(Noticia $noticia)
    {
        return view('admin.noticias.show', compact('noticia'));
    }

    public function create()
    {
        return view('admin.noticias.create');
    }

    public function store(StoreNoticiaRequest $request)
    {
        $data = $request->validated();
        $data['publicada'] = $request->has('publicada');

        // Manejar subida de imagen
        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('noticias', 'public');
        }

        Noticia::create($data);

        return redirect()->route('admin.noticias.index')
            ->with('success', 'Noticia creada exitosamente');
    }

    public function edit(Noticia $noticia)
    {
        return view('admin.noticias.edit', compact('noticia'));
    }

    public function update(UpdateNoticiaRequest $request, Noticia $noticia)
    {
        $data = $request->validated();
        $data['publicada'] = $request->has('publicada');

        // Manejar subida de imagen
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior
            if ($noticia->imagen) {
                Storage::disk('public')->delete($noticia->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('noticias', 'public');
        }

        $noticia->update($data);

        return redirect()->route('admin.noticias.index')
            ->with('success', 'Noticia actualizada exitosamente');
    }

    public function destroy(Noticia $noticia)
    {
        // Eliminar imagen si existe
        if ($noticia->imagen) {
            Storage::disk('public')->delete($noticia->imagen);
        }

        $noticia->delete();

        return redirect()->route('admin.noticias.index')
            ->with('success', 'Noticia eliminada exitosamente');
    }
}
