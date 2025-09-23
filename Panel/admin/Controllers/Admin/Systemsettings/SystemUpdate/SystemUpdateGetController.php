<?php

namespace App\Http\Controllers\Admin\Systemsettings\SystemUpdate;

use App\Http\Controllers\Controller;
use App\Traits\BaseTrait;
use Illuminate\Http\Request;

class SystemUpdateGetController extends Controller
{
    use BaseTrait;
    public function __construct()
    {
        $this->LoadMiddleware('auth:globuser',['HasSetPassword']);
    }

    public function viewSystemUpdate(Request $request)
    {
        return view('admin.pages.systemsettings.systemupdate.viewSystemUpdate');
    }
}
