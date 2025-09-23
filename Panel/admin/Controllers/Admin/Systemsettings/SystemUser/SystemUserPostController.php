<?php

namespace App\Http\Controllers\Admin\Systemsettings\SystemUser;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Systemsettings\User\ValidateCreateSystemUser;
use App\Repositories\Admin\Systemsettings\SystemUser\ISystemUserRepository;
use App\Traits\BaseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class SystemUserPostController extends Controller
{
    use BaseTrait;
    public function __construct(private ISystemUserRepository $ISystemUserRepo)
    {
        $this->LoadMiddleware('',['SetAdminLanguage']);
        $this->LoadModels(['Globuser']);
    }
    public function create(ValidateCreateSystemUser $request)
    {   
        $auth = $this->checkExists($request,"Globuser|uuid|created_by",['id','name']);
        if(empty($auth)) {
            return $this->response(['type'=>'noUpdate','title'=> 'Globuser not found']);
        }
        return $this->ISystemUserRepo->create($request,['created_by'=> $auth->id]);
    }
    public function delete(Request $request)
    {
        return $this->ISystemUserRepo->delete($request);
    }

    public function updateRow(Request $request)
    {
        return $this->ISystemUserRepo->updateRow($request);
    }

    public function update(Request $request)
    {
        return $this->ISystemUserRepo->update($request);
    }

}
