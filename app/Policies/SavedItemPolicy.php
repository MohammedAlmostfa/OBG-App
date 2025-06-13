<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\User;

class SavedItemPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function unSave(User $user,  $id)
    {
        return $user->savedItems()->where('item_id', $id)->exists();
    }
}
