<?php

namespace App\Providers;

use App\Services\PxCommandService;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Schema;
//vpx_imports
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        foreach (config('pxcommands.panels') as $panel => $panels) {
            if (Schema::hasTable($panel.'_user_roles')) {
                //vpx_attach
            }
        }
    }
}
