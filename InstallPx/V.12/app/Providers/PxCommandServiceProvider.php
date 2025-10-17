<?php

namespace App\Providers;

use App\Services\PxCommandService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use File;
use Route;
class PxCommandServiceProvider extends ServiceProvider
{

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(abstract: PxCommandService::class, concrete: function ($app) {
            return new PxCommandService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        /*
        * Check is setup config is correct
        */
        if(!is_array(config('pxcommands.panels'))) {
            die('Invalid pxcommand config');
        }

        //get the service class for commands
        $pxCommandService = app(PxCommandService::class);

        /*
        * Get the avaiable  panelss and feed assets to them
        */
        foreach (config('pxcommands.panels') as $panel => $panels) {

            //restrict if styles and scripts are not set or not array
            if(!isset($panels['styles']) || !is_array($panels['styles']) ||  !isset($panels['scripts']) || !is_array($panels['scripts'])) {
                die('Styles or scripts not defined or not an array for styles or scrips in pxcommands config for '.$panel);
            }

            //feed the styles to header resources
            View::composer($panel.'.includes.header-resource', function ($view) use($panels,$panel,$pxCommandService){
                $view->with('appStyles', implode("\n",$pxCommandService->generateScripts(panels: $panels,panel: $panel,from: 'styles')));
                //vpx_to_header
            });

            //feed the scripts to footer resources
            View::composer($panel.'.includes.footer-resource', function ($view) use($panels,$panel,$pxCommandService) {
                $view->with('appScripts', implode("\n",$pxCommandService->generateScripts(panels: $panels,panel: $panel,from: 'scripts')));
                //vpx_to_footer
                $systemPolicies = $pxCommandService?->getPolicies();
                $view->with('systemPolicies',$systemPolicies);
            });
        }
    }
}
