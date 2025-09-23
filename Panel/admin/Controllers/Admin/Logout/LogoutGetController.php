<?php

namespace App\Http\Controllers\Admin\Logout;

use App\Http\Controllers\Controller;
use App\Traits\BaseTrait;
use Auth;

class LogoutGetController extends Controller
{
    use BaseTrait;
    public function __construct()
    {
        $this->LoadMiddleware('auth:globuser',['HasSetPassword']);
    }
    public function logout()
    {
        Auth::guard('globuser')->logout();
        return redirect()->route('admin.login.home')->withErrors(["success" => [0 => "Succesfully logged out from the system"]]);
    }
}
