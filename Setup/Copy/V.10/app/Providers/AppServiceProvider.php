<?php

namespace App\Providers;

use App\Models\AppData;
use App\Models\Institute;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
//vpx_imports
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //vpx_app_register_service_providers
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //vpx_binds
        Paginator::useBootstrapFive();
    }
}
