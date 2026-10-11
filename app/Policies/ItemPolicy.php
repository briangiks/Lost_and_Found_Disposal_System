<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\User;

class ItemPolicy
{
    /** Both roles may browse and log items. */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Item $item): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Item $item): bool
    {
        return true;
    }

    /** Marking an item as collected by its owner — any officer may do this at the desk. */
    public function claim(User $user, Item $item): bool
    {
        return true;
    }

    /**
     * Deleting an item permanently removes it from the registry (and the
     * audit trail if it was ever disposed), so this is restricted to admins.
     */
    public function delete(User $user, Item $item): bool
    {
        return $user->isAdmin();
    }
}
