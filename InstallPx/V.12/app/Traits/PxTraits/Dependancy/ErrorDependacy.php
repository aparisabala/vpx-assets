<?php
namespace App\Traits\PxTraits\Dependancy;

use App\Models\SystemError;
use App\Models\SystemTracking;

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

     public function getTrackData($title,$request,$onlyTitle=false,$row=null,$type='from')
    {
        $rData = $request->all();
        $row = ($row == null) ? [] : $row;
        unset($rData['_token']);
        unset($rData['auth']);
        unset($rData['lang']);
        $fromData = "";
        $toData = NUll;
        if($onlyTitle) {
            $fromData = "";
            $toData = NUll;
        } else {
            if($type == 'from') {
                $fromData = implode(', ', array_map( fn($k, $v) => $k . ': ' . (is_array($v) ? implode(', ', $v) : $v), array_keys($rData),  $rData));
            }
            if($type == 'to') {
                $fromData = implode(', ', array_map(
                    fn($k, $v) => $k . ' : ' . (is_array($v) ? implode(', ', $v) : $v),
                    array_keys($row),
                    $row
                ));
                $toData = implode(', ', array_map(
                    fn($k, $v) => $k . ' : ' . (is_array($v) ? implode(', ', $v) : $v),
                    array_keys($rData),
                    $rData
                ));
            }
        }
        return [
            "title"      => $title,
            "from_data"  => $fromData,
            "to_data"  =>  $toData,
        ];
    }
     public function saveTractAction($data)
    {
        $i                = new SystemTracking;
        $i->title = $data['title'];
        $i->from_data   = $data['from_data'];
        $i->to_data    = (isset($data['to_data'])) ? $data['to_data'] : NULL;
        $i->save();
    }
}
