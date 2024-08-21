<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Balances\Service\ConcreteBalanceService;


class BalanceServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('balances', function ($app) {
            return new ConcreteBalanceService();
        });
    }
}
