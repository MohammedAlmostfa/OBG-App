<?php

namespace App\Policies;

use App\Models\User;

class FavouritrUserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
     public function remove(User $user,  $id)
    {
        return $user->favoriteUsers()->where('favorite_user_id', $id)->exists();
    }
}
