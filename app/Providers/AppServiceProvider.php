<?php

namespace App\Providers;

use App\Policies\FavouritrUserPolicy;
use App\Policies\SavedItemPolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
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
     Gate::define('unSave', [SavedItemPolicy::class, 'unSave']);
     Gate::define('remove', [FavouritrUserPolicy::class, 'remove']);
    }
}
