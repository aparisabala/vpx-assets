<?php

namespace App\Traits\PxTraits;

use App\Traits\PxTraits\Dependancy\AutoloadDependancy;
use App\Traits\PxTraits\Dependancy\CheckExistsDependancy;
use App\Traits\PxTraits\Dependancy\ElequentDependancy;
use App\Traits\PxTraits\Dependancy\ErrorDependacy;
use App\Traits\PxTraits\Dependancy\GlobalDependancy;
use App\Traits\PxTraits\Dependancy\MultipleImageVersioning;
use App\Traits\PxTraits\Dependancy\ResponseDependacy;

trait PxTraits
{
    //dependacy
    use AutoloadDependancy,ElequentDependancy,ResponseDependacy,ErrorDependacy, CheckExistsDependancy, GlobalDependancy, MultipleImageVersioning;

}
