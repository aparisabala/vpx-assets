<?php

namespace App\Repositories;

interface IBaseRepository
{
    public function getPageDefault($request,$id) :  array;
    public function facSrWc($request) :  int;
    
}
