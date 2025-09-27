<?php
namespace App\Traits\PxTraits\Dependancy;
trait CheckExistsDependancy
{
    public function checkExists($request,$data,$select=['*']){
        $values = explode("|",$data);
        $data = $this->{$values[0]}->getQuery([
            'type'=>'first',
            'query' => [
                'where' => [
                    [[
                        $values[1],'=',$request->{$values[2]}
                    ]]
                ]
                    ],
            'select' => $select
        ]);
        return $data;
    }

    public function checkInUse($op)
    {
        $errors = [];
        $rows = $op['rows'] ?? [];
        $search = $op['search']  ?? [];
        $targetModel = $op['targetModel']  ?? [];
        $targetCol = $op['targetCol']  ?? [];
        $denined = $op['denined']  ?? [];
        $exists = $op['exists']  ?? [];
        $in = $op['in']  ?? [];
        foreach ($targetModel as $key => $model) {
            $ids = $rows?->pluck($search[$key])?->toArray() ?? [];
            $targetIds = $model->whereIn($targetCol[$key],$ids)->select([$targetCol[$key]])->pluck($targetCol[$key])->toArray();
            if(count($rows) > 0) {
                foreach ($rows as $row_keys => $value) {
                    if(in_array($value[$search[$key]], $targetIds)) {
                        $errors[] = $exists[$key].' "' . $value[$denined[$key]] . '" allready in use in '.$in[$key].', can not be deleted ';
                        break;
                    }
                }
            }
        }
        return $errors;
    }

    public function checkBlukDirty($op)
    {   
        $request = $op['request'];
        $rows = $op['rows'] ?? [];
        $match = $op['match'] ?? [];
        $dirty = [];
        foreach ($rows as $key => $value) {
            foreach ($match as $matchKey => $matchValue) {
                if(isset($request->{$matchValue})) {
                    $given_value = $request->{$matchValue}[$value->id];
                    if($value->{$matchValue} != $given_value) {
                        $dirty[] = "yes";
                    }
                }
            }
        }
        return $dirty;
    }

    public function checkBlukValidation($op)
    {   
        $request = $op['request'] ?? [];
        $search = $op['search'] ?? [];
        $match = $op['match'] ?? [];
        $mapBy = $op['mapBy'] ?? "id";
        $errors = [];
        if(isset($request->{$search})) {
            $rowCount = 1;
            foreach ($request->{$search} as $key => $id) {
                foreach ($match as $item_key => $item_value) {
                    if(isset($request->{$item_key})) {
                        $given_value = $request->{$item_key}[${$mapBy}];
                        if(array_key_exists("empty",$item_value) && $item_value) {
                            if($given_value == "") {
                                $errors[] = $item_key." can not be empty in row ".($rowCount);
                                break;
                            }    
                        }
                        if(array_key_exists("number",$item_value) && $item_value) {
                            if(!is_numeric($given_value)) {
                                $errors[] = $item_key." must be a number in row ".($rowCount);
                                break;
                            }    
                        }
                        if(array_key_exists("min",$item_value) && $item_value) {
                            if($given_value < $item_value['min']) {
                                $errors[] = $item_key." can not be less than ".($item_value['min'])." in  row ".($rowCount);
                                break;
                            }    
                        }
                        if(array_key_exists("max",$item_value) && $item_value) {
                            if($given_value < $item_value['max']) {
                                $errors[] = $item_key." can not be greter than ".($item_value['max'])." in  row ".($rowCount);
                                break;
                            }    
                        }
                        if(array_key_exists("unique",$item_value)) {
                            $model =  $item_value['unique']['model'] ?? null;
                            $rows = $item_value['unique']['rows'] ?? [];
                            if($model != null && count($rows) > 0) {
                                $row = $rows->where('id','=',$id)->first();
                                if($row->{$item_key} !=  $given_value) {
                                    $uniqueWhere = [[$item_key,'=',$given_value]];
                                    if(isset($item_value['unique']['where']) && is_array($item_value['unique']['where'])) {
                                        foreach ($item_value['unique']['where'] as $unique_key => $unique_value) {
                                            $uniqueWhere[] = [$unique_value,'=',$row->{$unique_value}];
                                        }
                                    }
                                    $ex = $this->{$model}->getQuery(['type'=>'first','select'=>['id'],'query'=>['where'=>[$uniqueWhere]]]);
                                    if(!empty($ex)) {
                                        $errors[] = $item_key." is duplicate in  row ".($rowCount);
                                        break;
                                    }
                                }
                            } else {
                                $errors[] = $item_key." is found unique check but no proper information given in row".($rowCount);
                                break;
                            }
                        }
                    }
                }
                if(count($errors) > 0) {
                    break;
                }
                $rowCount++;
            }
        } else {
            $errors[] = "No items selected";
        }
        return $errors;
    }
}