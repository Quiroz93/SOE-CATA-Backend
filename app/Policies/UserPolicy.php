<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user) {
        return $user->can('usuarios.view');
    }
    public function view(User $user, User $model) {
        return $user->can('usuarios.view');
    }
    public function create(User $user) {
        return $user->can('usuarios.create');
    }
    public function update(User $user, User $model) {
        return $user->can('usuarios.update');
    }
    public function delete(User $user, User $model) {
        return $user->can('usuarios.delete');
    }
}
