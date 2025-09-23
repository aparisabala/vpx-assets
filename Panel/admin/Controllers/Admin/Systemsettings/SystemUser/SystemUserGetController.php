<?php

namespace App\Http\Controllers\Admin\Systemsettings\SystemUser;

use App\Http\Controllers\Controller;
use App\Traits\BaseTrait;
use Illuminate\Http\Request;

class SystemUserGetController extends Controller
{
    use BaseTrait;
    public function __construct()
    {
        $this->LoadMiddleware('auth:globuser',['HasSetPassword']);
        $this->LoadModels(['Globuser']);
    }
    public function viewSystemUser(Request $request)
    { 
        $data = getPageDefault("admin/systemsettings/config/info/",$request);
        if(isset($request->uuid)) {
            $data['item'] = $this->Globuser->getQuery(['type'=>'first','query'=>['where'=>[[['uuid','=',$request->uuid]]]]]);
        } else {
            $data['items'] =  $this->Globuser->getQuery(['type'=>'get','take'=>1,'query'=>['where'=>[[['admin_type','=','system']]]]]);
        }
        return view('admin.pages.systemsettings.systemuser.viewSystemUser')->with('data',$data);
    }
}
