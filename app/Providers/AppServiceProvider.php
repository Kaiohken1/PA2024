<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        View::composer('layouts.navigation', function ($view) {
            $user = User::findOrFail(auth()->id());
            $notifications = $user->notifications()->latest()->paginate(10);
            $view->with('notifications', $notifications)
            ->with('user', $user);
        });
    }
}
