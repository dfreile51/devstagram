<?php

namespace App\Providers;

use App\Policies\PerfilPolicy;
use Illuminate\Support\Facades\Gate;
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
        Gate::define('view', [PerfilPolicy::class, 'view']);
        Gate::define('update', [PerfilPolicy::class, 'update']);
    }
}
