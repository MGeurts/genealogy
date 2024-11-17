<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\Userlog;
use Illuminate\Auth\Events\Login;
use Stevebauman\Location\Facades\Location;

class UserLogin
{
    /**
     * Handle the login event.
     */
    public function handle(Login $event): void
    {
        $user = $event->user;

        // -----------------------------------------------------------------------
        // Set language and timezone
        // -----------------------------------------------------------------------
        session([
            'locale'   => $user->language ?? config('app.locale', 'en'),
            'timezone' => $user->timezone ?? config('app.timezone', 'UTC'),
        ]);

        // -----------------------------------------------------------------------
        // Update user's last seen timestamp
        // -----------------------------------------------------------------------
        $user->timestamps = false;
        $user->seen_at    = now()->getTimestamp();
        $user->saveQuietly();

        // -----------------------------------------------------------------------
        // Log user location (only in production)
        // -----------------------------------------------------------------------
        if (app()->isProduction()) {
            $this->logUserLocation($user->id);
        }
    }

    /**
     * Log the user's location.
     */
    private function logUserLocation(int $userId): void
    {
        if ($position = Location::get()) {
            Userlog::create([
                'user_id'      => $userId,
                'country_name' => $position->countryName ?? null,
                'country_code' => $position->countryCode ?? null,
            ]);
        }
    }
}
