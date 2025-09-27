<?php

use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Support\Arr;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
return function (Exceptions $exceptions) {

    $exceptions->render(function (Throwable $exception,$request) {
        if ($exception instanceof AuthenticationException) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
            $guard = Arr::get($exception->guards(), 0);
            if (in_array($guard, [])) {
                return response()->json(['error' => 'Unauthenticated.', 'status' => 401], 401);
            } else {
                $login = 'site.home';
                switch ($guard) {
                    //vpx_guard_logins
                    case 'admin':
                        $login = 'site';
                        break;
                    default:
                        break;
                }
                return redirect()->guest(route($login));
            }
        }
        return response()->view('errors.exception', [
            'exception' => $exception
        ], 500);
    });
    $exceptions->report(function (Throwable $e) {
        // Example: Sentry::captureException($e);
    });
};
