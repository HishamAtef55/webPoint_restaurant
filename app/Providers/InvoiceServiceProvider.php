<?php

namespace App\Providers;

use App\Invoices\Invoice;
use Illuminate\Support\ServiceProvider;
use App\Invoices\Invoice\InvoiceInterface;

class InvoiceServiceProvider extends ServiceProvider

{

    /**
     * Register any application services.
     */
    public function register()
    {

        $this->app->singleton(InvoiceInterface::class, function () {
            return new Invoice(session(), request());
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {}
}
