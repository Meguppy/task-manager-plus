<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;

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
        //
        Paginator::useBootstrapFive();
        Carbon::setLocale(config('app.locale'));
        setlocale(LC_ALL, 'ja_JP.UTF-8');
        // Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        JsonResource::withoutWrapping();
    }
}
