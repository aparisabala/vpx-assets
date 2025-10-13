<?php
namespace App\Traits\PxTraits\Dependancy;

use App\Models\SystemError;

trait ErrorDependacy
{
    public function saveError($data,$e)
    {
        $i                = new SystemError;
        $i->module_name   = $data['name'];
        $i->sub_module    = $data['sub_name'];
        $i->sk_module_id  = $data['id'];
        $i->map_table     = $data['map_table'];
        $i->error_message = $e->getMessage();
        $i->save();
    }

    public function getSystemError($params)
    {
        return [
            "name"      => $params['name'],
            "sub_name"  => (isset($params['sub_name'])) ?  $params['sub_name'] :  "",
            "id"        => (isset($params['id'])) ?  $params['id'] :  0,
            "map_table" => (isset($params['map_table'])) ?  $params['map_table'] :  "no",
        ];
    }
}
