<?php
namespace App\Traits\PxTraits\Dependancy;
use Illuminate\Support\Facades\Validator;
trait GlobalDependancy
{
    public function creatDir($dir)
    {
        if (!is_dir($dir)) {
            mkdir($dir);
        }
    }

    public function inlineValidate($request,$op=[])
    {   
        $rules = $op['rules'] ?? [];
        $messages = $op['messages'] ?? [];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return $validator->errors();
        }
        return [];
    }
}
