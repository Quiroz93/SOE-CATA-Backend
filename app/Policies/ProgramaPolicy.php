<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Programa;

class ProgramaPolicy
{
    private function isAdmin(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('Super Admin') || $user->hasRole('Administrador');
    }

    public function viewAny(User $user) {
        return $this->isAdmin($user) || $user->can('programas.view');
    }
    public function view(User $user, Programa $programa) {
        return $this->isAdmin($user) || $user->can('programas.view');
    }
    public function create(User $user) {
        return $this->isAdmin($user) || $user->can('programas.create');
    }
    public function update(User $user, Programa $programa) {
        return $this->isAdmin($user) || $user->can('programas.update');
    }
    public function delete(User $user, Programa $programa) {
        return $this->isAdmin($user) || $user->can('programas.delete');
    }
}
