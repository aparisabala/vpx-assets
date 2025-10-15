<?php

namespace App\Repositories;

use App\Traits\BaseTrait;

class BaseRepository implements IBaseRepository
{
    use BaseTrait;
    public function __construct()
    {
    }

    /**
     * Get index page default data to perform basic cheakes on how page shoud render
     *
     * @param Elequent $model
     * @param integer|string|null $id
     * @return void
     */
    public function getPageDefault($query,$id,$where=[]) : array
    {
        $data =  ['item' => null,'type' => 'add', 'items' => []];
        if(count($where) > 0) {
            $query = $query->where($where);
        }
        return ($id == null) ? [...$data, 'items' => $query->take(1)->select(['id'])->get()] :  [...$data, 'type' => 'edit', 'item' => $query->find($id)];
    }

    /**
     * Return last inserted serial with plus 1
     *
     * @param Elequent $model
     * @param array $data
     * @return Integer
     */
    public function facSrWc($model,$data=[]) : int
    {
        $serial = 1;
        $q = [
            'type'=>'first',
            'query'=>[
                'latest'=>['serial']
            ],
            'select' => ['id','serial']
        ];
        if(isset($data['where'])) {
            $q = [
                ...$q,
                'query' => [
                    ...$q['query'],
                    'where' => $data['where']
                ]
            ];
        }
        $s = $model->getQuery($q);
        if(empty($s)) {
            $serial = 1;
        } else {
            $serial = $s->serial+1;
        }
        return $serial;
    }
}
