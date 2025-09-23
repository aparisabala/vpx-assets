<?php

namespace App\Http\Controllers\Admin\Login;

use App\Http\Controllers\Controller;
use App\Traits\BaseTrait;
use Illuminate\Http\Request;

class LoginGetController extends Controller
{
    use BaseTrait;
    public function __construct()
    {
        $this->LoadMiddleware('guest:globuser');
    }
    /**
     * View login page
     *
     * @param Request $request
     * @return View
     */
    public function viewLogin(Request $request) : View
    {   $data = [];
        if(isset($_GET['pass_changed']) && $_GET['pass_changed']) {
            $data = [
                "success" => [ "Your password changed succesfully, please login to continue"]
            ];
        }
        return view('admin.pages.login.viewLogin')->withErrors($data);
    }
}
