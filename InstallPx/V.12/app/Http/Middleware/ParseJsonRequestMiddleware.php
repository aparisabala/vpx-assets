<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ParseJsonRequestMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->isJson()) {
            $data = json_decode($request->getContent(), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $request->replace($data);
            }
        }

        return $next($request);
    }
}
