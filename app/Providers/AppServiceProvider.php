<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Square\SquareClient;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->bind(SquareClient::class, function ($app) {
            return new SquareClient([
                'accessToken' => config('services.square.access_token'),
                'environment' => config('services.square.environment'),
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}
