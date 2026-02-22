<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Oferta;

class OfertaPolicy
{
    public function viewAny(User $user) {
        return $user->can('ofertas.view');
    }
    public function view(User $user, Oferta $oferta) {
        return $user->can('ofertas.view');
    }
    public function create(User $user) {
        return $user->can('ofertas.create');
    }
    public function update(User $user, Oferta $oferta) {
        return $user->can('ofertas.update');
    }
    public function delete(User $user, Oferta $oferta) {
        return $user->can('ofertas.delete');
    }
}
