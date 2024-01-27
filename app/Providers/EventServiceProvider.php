<?php

namespace App\Providers;

use App\Models\Userlog;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Stevebauman\Location\Facades\Location;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Event::listen(\Illuminate\Auth\Events\Login::class, function ($event) {
            $this->logUser($event->user);
        });
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }

    private function logUser($user)
    {
        try {
            if ($position = Location::get()) {
                $country_name = $position->countryName;
                $country_code = $position->countryCode;
            } else {
                $country_name = null;
                $country_code = null;
            }

            // To Do : Remove the kreaweb filter in production
            if ($user->email != 'kreaweb@genealogy.test') {
                Userlog::create([
                    'user_id' => $user->id,
                    'country_name' => $country_name,
                    'country_code' => $country_code,
                ]);
            }
        } catch (QueryException $e) {
            Log::info("User log ERROR: {$e->getMessage()}");
        }
    }
}
