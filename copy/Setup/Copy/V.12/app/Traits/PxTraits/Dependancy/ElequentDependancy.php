<?php

namespace App\Traits\PxTraits\Dependancy;

trait ElequentDependancy
{
    public function getQuery($params)
    {
        $query = $this->query();
        if (isset($params['query']) &&  count($params['query']) > 0) {
            $query = $this->buildQuery($query, $params);
        }
        if (isset($params['sum_of'])) {
            return $query->select($this->getSelected($params))->{$params['type']}($params['sum_of']);
        }
        if (isset($params['take'])) {
            $query = $query->take($params['take']);
        }
        if (isset($params['orderBy'])) {
            if(is_array($params['orderBy'])){
                $query = $query->orderBy($params['orderBy'][0],$params['orderBy'][1]);
            }
        }
        if (isset($params['withTrashed']) && $params['withTrashed'] == "yes") {
            $query = $query->withTrashed();
        }
        if (isset($params['onlyTrashed']) && $params['onlyTrashed'] == "yes") {
            $query = $query->onlyTrashed();
        }
        if (isset($params['chunk'])) {
            $query->select($this->getSelected($params))->chunk($params['chunk'], function($all) use (&$data ) {
                foreach ($all as $single){$data[]=$single;}
            });
            return collect($data);
        }
        return $query->select($this->getSelected($params))->{$params['type']}();
    }
    protected function buildQuery($query, $params)
    {
        $query = $query;
        foreach ($params['query'] as $p_key => $p_value) {
            if (count($p_value) > 0) {
                foreach ($p_value as $key => $value) {
                    if (in_array($p_key, ['where','latest'])) {
                        $query = $query->{(string)$p_key}($value);
                    }
                    if (in_array($p_key, ['whereDate'])) {
                        $query = $query->{(string)$p_key}($value[0], $value[1], $value[2]);
                    }
                    if (in_array($p_key, ['whereIn','whereNotIn', 'whereDay', 'whereMonth', 'whereYear','whereBetween'])) {
                        $query = $query->{(string)$p_key}($value[0], $value[1]);
                    }
                    if (in_array($p_key, ['with'])) {
                        $query = $query->{(string)$p_key}([$value['name'] => function ($q) use ($value) {
                            if (isset($value['select'])) {
                                $q->select($value['select']);
                            }
                            if (isset($value['where'])) {
                                $q->where($value['where']);
                            }
                            if (isset($value['whereIn'])) {
                                $q->whereIn($value['whereIn'][0],$value['whereIn'][1]);
                            }
                            if (isset($value['orderBy'])) {
                                $q->orderBy((isset($value['orderBy']['column']) ? $value['orderBy']['column'] : "id"), (isset($value['orderBy']['DESC'])) ? $value['orderBy']['DESC'] : "ASC");
                            }
                        }]);
                    }
                }
            }
        }
        return $query;
    }
    protected function getSelected($params)
    {
        return (isset($params['select'])) ? $params['select'] : ['*'];
    }
}
