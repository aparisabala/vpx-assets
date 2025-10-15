<?php

namespace App\Repositories;

interface IBaseRepository
{
    public function getPageDefault($query,$data,$where=[]);
    public function facSrWc($model,$data);
}
