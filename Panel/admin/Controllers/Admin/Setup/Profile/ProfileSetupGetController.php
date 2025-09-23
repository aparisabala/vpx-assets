<?php

namespace App\Http\Controllers\Admin\Setup\Profile;

use App\Http\Controllers\Controller;
use App\Traits\BaseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileSetupGetController extends Controller
{
    use BaseTrait;
    public function __construct()
    {
        $this->LoadMiddleware('auth:globuser');
    }

    public function viewProfileSetup(Request $request)
    {
        $user = Auth::user();
        if($user->setup_done == "yes") {
            return redirect()->route('admin.dashboard');
        }
        $data = getPageDefault("admin/setup/profile",$request);
        $data['item'] = $user;
        return  view('admin.pages.setup.viewProfileSetup')->with("data",$data); 
    }
}
