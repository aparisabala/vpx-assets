<?php

namespace App\Services;
use File;
use Route;
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
        if($from == "scripts") {
            $data = [
                '<script src="'.url('js/config/config.js').'"></script>',
                '<script src="'.url('js/config/defaultConfig.js').'"></script>'
            ];
        }
        $data =  [
            ...$data,
            ...$this?->getCdn(panels: $panels, getFrom: $from),
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
                print '<script src="' . url('components/' . $value) . '?ver=' .V. '"></script>' . PHP_EOL;
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
    public function appendRoutes($dir) : void
    {
        $routeFiles = $this->getFilesRecursively(base_path($dir),$dir);
        foreach ($routeFiles as $value) {
            Route::middleware('web')->group(base_path((string)$value));
        }
    }
}
