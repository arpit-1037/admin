<?php

namespace App\Listeners;

use App\Events\UserDeactivated;
use Illuminate\Support\Facades\Log;

class HandleUserDeactivation
{
    public function handle(UserDeactivated $event): void
    {
        $user = $event->user;

        // 1️⃣ Log activity
        Log::info("User {$user->email} has been deactivated.");

        // 2️⃣ Optional: send notification email
        // Mail::to($user->email)->queue(new AccountDeactivatedMail($user));

        // 3️⃣ Optional: clear user-related cache
        // Cache::forget('user_order_count_'.$user->id);
    }
}