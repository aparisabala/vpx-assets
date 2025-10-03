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
    public function getPageDefault($model,$id) : array
    {
        $data =  ['item' => null,'type' => 'add', 'items' => []];
        return ($id == null) ? [...$data, 'items' => $model::take(1)->select(['id'])->get()] :  [...$data, 'type' => 'edit', 'item' => $model::find($id)];
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
