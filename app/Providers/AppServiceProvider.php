<?php

namespace App\Providers;

use App\Models\User;
use Laravel\Cashier\Cashier;
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
            if($user = User::findOrFail(auth()->id())) {
                $notifications = $user->notifications()->latest()->paginate(10);
                $view->with('notifications', $notifications)
                ->with('user', $user);
            }
        
        Carbon::setLocale('fr');
        });

        Cashier::calculateTaxes();
    }
}
