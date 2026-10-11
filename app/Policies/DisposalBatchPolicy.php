<?php

namespace App\Policies;

use App\Models\DisposalBatch;
use App\Models\User;

class DisposalBatchPolicy
{
    public function view(User $user, DisposalBatch $batch): bool
    {
        return true;
    }

    /** Sending the Communications notice is routine — any officer can trigger it. */
    public function sendNotice(User $user, DisposalBatch $batch): bool
    {
        return true;
    }

    /**
     * Releasing a batch permanently hands university property to an outside
     * party and cannot be undone, so it's restricted to admins.
     */
    public function release(User $user, DisposalBatch $batch): bool
    {
        return $user->isAdmin();
    }
}
