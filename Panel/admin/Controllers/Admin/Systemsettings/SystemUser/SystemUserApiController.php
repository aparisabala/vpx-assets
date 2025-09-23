<?php

namespace App\Http\Controllers\Admin\Systemsettings\SystemUser;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\Systemsettings\SystemUser\ISystemUserRepository;
use App\Traits\BaseTrait;
use Illuminate\Http\Request;
class SystemUserApiController extends Controller
{
    use BaseTrait;
    public function __construct(private ISystemUserRepository $ISystemUserRepo)
    {
        $this->LoadModels(['Globuser']);
    }
    public function list(Request $request)
    {
        return $this->ISystemUserRepo->list($this->Globuser,$request);
    }
}
