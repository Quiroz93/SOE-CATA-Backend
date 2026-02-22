<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Inscrito;

class InscritoPolicy
{
    public function viewAny(User $user) {
        return $user->can('matriculas.view');
    }
    public function view(User $user, Inscrito $inscrito) {
        return $user->can('matriculas.view');
    }
    public function create(User $user) {
        return $user->can('matriculas.create');
    }
    public function update(User $user, Inscrito $inscrito) {
        return $user->can('matriculas.update');
    }
    public function delete(User $user, Inscrito $inscrito) {
        return $user->can('matriculas.delete');
    }
}
