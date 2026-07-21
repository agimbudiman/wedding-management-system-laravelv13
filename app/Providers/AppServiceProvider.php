<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Log successful logins
        Event::listen(Login::class, function (Login $event) {
            Log::info('User Logged In', [
                'user_id' => $event->user->id,
                'email'   => $event->user->email ?? $event->user->username ?? null,
                'ip'      => Request::ip(),
                'agent'   => Request::userAgent(),
            ]);
        });

        // Log logouts
        Event::listen(Logout::class, function (Logout $event) {
            if ($event->user) {
                Log::info('User Logged Out', [
                    'user_id' => $event->user->id,
                    'email'   => $event->user->email ?? $event->user->username ?? null,
                    'ip'      => Request::ip(),
                ]);
            }
        });

        // Log failed login attempts
        Event::listen(Failed::class, function (Failed $event) {
            // Remove password field for security before logging
            $credentials = $event->credentials;
            if (isset($credentials['password'])) {
                unset($credentials['password']);
            }

            Log::warning('Failed Login Attempt', [
                'credentials' => $credentials,
                'ip'          => Request::ip(),
                'agent'       => Request::userAgent(),
                'user_exists' => $event->user ? 'Yes (Wrong Password)' : 'No (User Not Found)',
            ]);
        });
    }
}

