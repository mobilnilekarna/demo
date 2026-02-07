<?php

namespace App\Providers;

use App\Context\Sources\SupplierContext;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(SupplierContext::class, fn () => new SupplierContext(
            file: '',
            type: 'xml',
        ));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
