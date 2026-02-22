<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Preinscrito;

class PreinscritoPolicy
{
    public function viewAny(User $user) {
        return $user->can('inscripciones.view');
    }
    public function view(User $user, Preinscrito $preinscrito) {
        return $user->can('inscripciones.view');
    }
    public function create(User $user) {
        return $user->can('inscripciones.create');
    }
    public function update(User $user, Preinscrito $preinscrito) {
        return $user->can('inscripciones.update');
    }
    public function delete(User $user, Preinscrito $preinscrito) {
        return $user->can('inscripciones.delete');
    }
}
