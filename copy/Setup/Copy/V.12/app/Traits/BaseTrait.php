<?php

namespace App\Traits;

use App\Traits\Apis\Connection\MultiConnect;
use App\Traits\Apis\Sms\ApiSms;
use App\Traits\PxTraits\PxTraits;
//vpx_import
trait BaseTrait
{
    //dependacy
    use PxTraits;

    //api
    use ApiSms, MultiConnect;

    //vpx_attach
}
