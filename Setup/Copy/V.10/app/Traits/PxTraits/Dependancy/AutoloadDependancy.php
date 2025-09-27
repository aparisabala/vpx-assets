<?php

namespace App\Traits\PxTraits\Dependancy;

trait AutoloadDependancy
{
    public function LoadMiddleware($def, $mid = [])
    {
        $def_mid =  ($def == "") ?  [] : [$def];
        if (count($mid) > 0) {
            $def_mid = array_merge($def_mid, $mid);
        }
        $this->middleware($def_mid);
    }
    public function LoadModels($given = [])
    {
        $models = [];
        if (count($given) == 0) {
            $models = $this->availableModels();
        } else {
            $models = $this->selectedModels($given);
        }
        foreach ($models as $key => $value) {
            $name_space = $this->getNameSpace($key);
            $class_name    = $name_space . $value;
            $ref          = $this->getRefName($value);
            $this->{$ref} = new $class_name;
        }
    }

    private function availableModels()
    {
        return [];
    }

    private function selectedModels($given)
    {
        return $given;
    }

    public function getRefName($value)
    {
        return implode('', array_map(function ($v) {
            return ucfirst($v);
        }, explode("_", $value)));
    }

    private function getNameSpace($key)
    {
        return (is_numeric($key)) ? 'App\\Models\\' : $key;
    }
}
