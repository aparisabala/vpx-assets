<?php

namespace App\Services;

use App\Models\AdminUser;
use App\Models\AdminUserPermission;
use File;
use Route;
use Lang;
use Illuminate\Support\Facades\Gate;
class PxCommandService
{

    /*
    * Get  css/js from cdn, local or conditional
    * @param array $panels
    * @param string $panel
    * @param string $from
    * @return array
    */
    public function generateScripts($panels,$panel,$from) : array
    {
        $data = [];
        if(env('HAS_SOCKET')) {
            $data = [
                ...$data,
                '<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.8.1/socket.io.js" integrity="sha512-8BHxHDLsOHx+flIrQ0DrZcea7MkHqRU5GbTHmbdzMRnAaoCIkZ97PqZcXJkKZckMMhqfoeaJE+DNUVuyoQsO3Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>'
            ];
        }
        if($from == "scripts") {
            $data = [
                ...$data,
                '<script src="'.url('js/config/config.js').'"></script>',
                '<script src="'.url('js/config/defaultConfig.js').'"></script>'
            ];
        }
        $data =  [
            ...$data,
            ...$this?->getCdn(panels: $panels, getFrom: $from),
        ];
         if(env('PX_DEBUG')) {
            $data = [
                ...$data,
                ($from == 'styles') ?  '<link rel="stylesheet" href="/../../vpx-assets/PxLib/px-ajax/dist/px.css"/>' : '<script src="/../../vpx-assets/PxLib/px-ajax/dist/px.js"></script>'
            ];
        }
        $data = [
            ...$data,
            ...$this?->getLocal(panels: $panels, panel: $panel, getFrom: $from),
            ...$this?->getLocal(panels: $panels, panel: $panel,  getFrom: $from, conditinal: 'yes'),
        ];
        return $data;
    }


    /*
    * Get the styles/script strem from config
    * @param array $panels
    * @param string $getFrom
    * @return array
    */
    public function getCdn($panels,$getFrom) : array
    {
        $data = [];
        $cdns = config('pxcommands.'.$getFrom.'.cdn');
        if(isset($panels[$getFrom]['cdns'])) {
            foreach ($panels[$getFrom]['cdns'] as $key => $script) {
                if($script == "tailwind") {
                    $data[] = '<link ref="preconnect" href="'.url('components/app.css').'" />';
                } else {
                    if(isset($cdns[$script])) {
                        $data[] = $cdns[$script];
                    }
                }
            }
        }
        return $data;
    }

    /*
    * Get the styles/script strem from config
    * @param array $panels
    * @param string $getFrom
    * @return array
    */
    public function getLocal($panels,$panel,$getFrom,$conditinal="no") : array
    {
        $from = ($getFrom == 'scripts') ? 'js':'css';
        $data = [];
        if($conditinal == "no") {

            if(isset($panels[$getFrom]['local'])) {

                foreach ($panels[$getFrom]['local'] as $key => $folder) {
                    $dir = "$from/$folder";
                    if(is_dir($dir)) {
                        if(env('PX_DEBUG') && $folder == 'px') {
                            continue;
                        }
                        $files = collect(File::files(public_path($dir)))
                        ->filter(fn($file) => $file->getExtension() === $from)
                        ->map(fn($file) => $file->getFilename())
                        ->values();
                        foreach ($files as $key => $file) {
                            $data[] = ($from == "js") ? '<script src="'.url("$dir/$file").'?v='.V.'"></script>' : '<link rel="stylesheet" href="'.url("$from/$folder/$file").'?v='.V.'"></link>';
                        }
                    }
                }
            }

        } else {

            if(isset($panels[$getFrom]['conditional'])) {
                $r = explode("/",Route::getFacadeRoot()->current()->uri());
                if(isset($r[1]) && in_array($r[1],$panels[$getFrom]['conditional'])) {
                    $dirs = ["$from/$panel/$r[1]","$from/$panel/$r[1]/calls"];
                    foreach ($dirs as $key => $dir) {
                         if(is_dir($dir)) {
                            $files = collect(File::files(public_path($dir)))
                            ->filter(fn($file) => $file->getExtension() === $from)
                            ->map(fn($file) => $file->getFilename())
                            ->values();
                            foreach ($files as $key => $file) {
                                $data[] = ($from == "js") ? '<script src="'.url("$dir/$file").'?v='.V.'"></script>' : '<link rel="stylesheet" href="'.url("$dir/$file").'?v='.V.'"></link>';
                            }
                        }
                    }
                }
            }
        }
        return $data;
    }

    /*
    * Get all the front end component and serve to blade
    * @param array $react
    * @return void
    */
    public static function appComponent($rect=[]) : void
    {
        if (count($rect) > 0) {
            foreach ($rect as $key => $value) {
                print '<script src="' . url('resources/js/' . $value) . '?ver=' .V. '"></script>' . PHP_EOL;
            }
        }
    }

    /*
    * Get files from the folders recursively
    * @param string $direcory
    * @param string $prepend
    * @return array
    */
    function getFilesRecursively($directory, $prepend) : array
    {
        $files = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($iterator as $fileInfo) {
            if ($fileInfo->isFile()) {
                $relativePath = $prepend . "/" . ltrim(str_replace($directory, '', $fileInfo->getPathname()), DIRECTORY_SEPARATOR);
                $files[] = $relativePath;
            }
        }
        return $files;
    }

    /*
    * Append routes to applications
    * @param string $dir
    * @return array
    */
    public function appendRoutes($dir,$group='web') : void
    {
        $routeFiles = $this->getFilesRecursively(base_path($dir),$dir);
        foreach ($routeFiles as $value) {
            Route::middleware($group)->group(base_path((string)$value));
        }
    }

    /**
     * Serves language packs to system
     *
     * @param string $key
     * @param string $value
     * @param string $common
     * @return string
     */
    public function pxLang($key='',$value='',$common='') : string
    {
        if($common != '') {
            return Lang::get($common);
        }
        if($key == '') {
            return '';
        }
        $lang = config('pxcommands.language');
        if(array_key_exists($key,$lang)) {
            return Lang::get($lang[$key].'.'.$value);
        }
        return '';
    }

    /**
     * Get current logged in user policy
     *
     * @return array
     */
    public function getPolicies() : array
    {
        $user = auth()->user();
        $abilities = Gate::abilities();
        $permissions = [];
        foreach (array_keys($abilities) as $slug) {
            $permissions[$slug] = Gate::forUser($user)->allows($slug);
        }
        return $permissions;
    }

    /**
     * Set user policy gate
     *
     * @param Query $policyModelQuery
     * @return void
     */
    public function registerPolicies($policyModelQuery) : void
    {
        $permissions = $policyModelQuery::select(['slug','user_access'])->get();
        foreach ($permissions as $permission) {
            Gate::define($permission->slug, function () use ($permission) {
                return auth()->user()->hasPermission($permission?->user_access ?? []) ;
            });
        }
    }
}
