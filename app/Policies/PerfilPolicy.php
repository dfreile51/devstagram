<?php

namespace App\Policies;

use App\Models\User;

class PerfilPolicy
{
    public function view(User $user, User $profileUser): bool
    {
        return $user->id === $profileUser->id;
    }

    public function update(User $user, User $profileUser): bool
    {
        return $user->id === $profileUser->id;
    }
}
