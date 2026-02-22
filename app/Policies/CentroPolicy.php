<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Centro;

class CentroPolicy
{
    public function viewAny(User $user) {
        return $user->can('programas.view');
    }
    public function view(User $user, Centro $centro) {
        return $user->can('programas.view');
    }
    public function create(User $user) {
        return $user->can('programas.create');
    }
    public function update(User $user, Centro $centro) {
        return $user->can('programas.update');
    }
    public function delete(User $user, Centro $centro) {
        return $user->can('programas.delete');
    }
}
