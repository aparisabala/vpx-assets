<?php

namespace App\Http\Middleware;

use App\Models\AppData;
use App\Traits\BaseTrait;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use File;
class SetBootConfig
{
    use BaseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $app_data = AppData::where('id',1)->select(['*'])->first();
        if(!empty($app_data) ) {
            config(['a' => $app_data]);
            config(['i' => [
                    'service_name' => "Service Name",
                    "service_domain"=> "domain.com",
                    "address"=>"Address",
                    "mobile_number" =>"01XXX0017454",
                    "service_email" =>"email@domain.com",
                    'logo'=>url('images/system/logo.png'),
                    'favicon'=>url('images/system/favicon.png'),
                ]
            ]);
            foreach (uploadsDir() as $key => $path) {
                if(!is_dir($path)) {
                    File::makeDirectory($path, 0755, true);
                }
            }
        } else {
            die('Something went wrong, contact webmaster, Error code: system 404');
        }
        return $next($request);
    }
}
