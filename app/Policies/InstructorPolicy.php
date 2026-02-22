<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Instructor;

class InstructorPolicy
{
    public function viewAny(User $user) {
        return $user->can('usuarios.view');
    }
    public function view(User $user, Instructor $instructor) {
        return $user->can('usuarios.view');
    }
    public function create(User $user) {
        return $user->can('usuarios.create');
    }
    public function update(User $user, Instructor $instructor) {
        return $user->can('usuarios.update');
    }
    public function delete(User $user, Instructor $instructor) {
        return $user->can('usuarios.delete');
    }
}
