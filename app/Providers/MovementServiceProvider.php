<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Movements\Service\ConcreteMovementService;

class MovementServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('movements', function ($app) {
            return new ConcreteMovementService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
