<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\Userlog;
use Illuminate\Auth\Events\Login;
use Stevebauman\Location\Facades\Location;

class UserLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        // -----------------------------------------------------------------------
        // set language
        // -----------------------------------------------------------------------
        $locale = auth()->user()->language ? auth()->user()->language : env('APP_LOCALE', 'en');

        session()->put('locale', $locale);

        // -----------------------------------------------------------------------
        // log user (seen_at)
        // -----------------------------------------------------------------------
        $event->user->timestamps = false;
        $event->user->seen_at    = now()->getTimestamp();
        $event->user->saveQuietly();

        // -----------------------------------------------------------------------
        // log user (only in production)
        // -----------------------------------------------------------------------
        if (app()->isProduction()) {
            if ($position = Location::get()) {
                $country_name = $position->countryName;
                $country_code = $position->countryCode;
            } else {
                $country_name = null;
                $country_code = null;
            }

            Userlog::create([
                'user_id'      => $event->user->id,
                'country_name' => $country_name,
                'country_code' => $country_code,
            ]);
        }
        // -----------------------------------------------------------------------
    }
}
