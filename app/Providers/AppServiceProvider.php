<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

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
        Paginator::useTailwind();

        // Log Login Activity
        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Auth\Events\Login::class,
            function ($event) {
                \App\Models\ActivityLog::log(
                    action: 'login',
                    module: 'auth',
                    description: 'User logged into the system',
                    userId: $event->user->id
                );
            }
        );

        // Log Logout Activity
        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Auth\Events\Logout::class,
            function ($event) {
                if ($event->user) {
                    \App\Models\ActivityLog::log(
                        action: 'logout',
                        module: 'auth',
                        description: 'User logged out of the system',
                        userId: $event->user->id
                    );
                }
            }
        );
    }
}
