<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Competencia;

class CompetenciaPolicy
{
    public function viewAny(User $user) {
        return $user->can('programas.view');
    }
    public function view(User $user, Competencia $competencia) {
        return $user->can('programas.view');
    }
    public function create(User $user) {
        return $user->can('programas.create');
    }
    public function update(User $user, Competencia $competencia) {
        return $user->can('programas.update');
    }
    public function delete(User $user, Competencia $competencia) {
        return $user->can('programas.delete');
    }
}
