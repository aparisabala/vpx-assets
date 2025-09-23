<?php

namespace App\Http\Controllers\Admin\Reset;

use App\Http\Controllers\Controller;
use App\Traits\BaseTrait;
use Illuminate\Http\Request;

class ResetGetController extends Controller
{
    use BaseTrait;
    public function __construct()
    {
        $this->LoadMiddleware('guest:globuser');
    }

    public function viewReset(Request $request)
    {
        return view('admin.pages.reset.viewReset');

    }
}
