<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function edit(User $user)
    {
        // Add your authorization logic here
        return $user->id === auth()->user()->id;
    }
}