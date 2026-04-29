<?php

namespace App\Traits;

use App\Models\CustomUserLog;

trait CustomUserLogTrait
{
    public function createCustomUserLog(string $action): void
    {
        CustomUserLog::create([
            'user_id' => auth()->user()->id,
            'action' => $action,
            'user_profile_id' => auth()->user()->profile_id
        ]);
    }
}
