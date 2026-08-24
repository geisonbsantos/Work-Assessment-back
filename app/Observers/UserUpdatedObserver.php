<?php

namespace App\Observers;

use App\Events\UserUpdatedEvent;
use App\Models\User;

class UserUpdatedObserver
{
    public function created(User $user): void
    {
        $this->clearCacheAndBroadcast('created', $user);
    }

    public function updated(User $user): void
    {
        if ($user->wasChanged('deleted_at')) {
            return;
        }
        $this->clearCacheAndBroadcast('updated', $user);
    }

    public function deleted(User $user): void
    {
        $this->clearCacheAndBroadcast('deleted', $user);
    }

    public function restored(User $user): void
    {
        $this->clearCacheAndBroadcast('restored', $user);
    }

    private function clearCacheAndBroadcast(string $action, User $user): void
    {
        // Event the event to all connected clients
        event(new UserUpdatedEvent($action, $user));
    }
}
