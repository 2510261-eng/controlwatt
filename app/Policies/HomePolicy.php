<?php

namespace App\Policies;

use App\Models\Home;
use App\Models\User;

class HomePolicy
{
    public function view(User $user, Home $home): bool
    {
        return $home->user_id === $user->id || $home->members()->where('users.id', $user->id)->exists();
    }
}
