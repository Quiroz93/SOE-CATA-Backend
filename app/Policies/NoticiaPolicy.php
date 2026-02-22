<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Noticia;

class NoticiaPolicy
{
    public function viewAny(User $user) {
        return $user->can('contenido.view');
    }
    public function view(User $user, Noticia $noticia) {
        return $user->can('contenido.view');
    }
    public function create(User $user) {
        return $user->can('contenido.create');
    }
    public function update(User $user, Noticia $noticia) {
        return $user->can('contenido.update');
    }
    public function delete(User $user, Noticia $noticia) {
        return $user->can('contenido.delete');
    }
}
