<?php

namespace App\Traits\PxTraits\Policies\Items;

trait HrmUserPolicyTrait {

    public function hrmUserPolicies(){
        return [
            'name' => 'Hrm Management',
            'policies' => [
                [
                    'name' => 'Hrm User Roles',
                    'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']
                ],
                [
                    'name' => 'Hrm User',
                    'keys' => ['view','store','bulk_update','delete','pdf','excel','edit','pass_change']
                ],
                [
                    'name' => 'Hrm User Policies',
                    'keys' => ['view','edit']
                ]
            ]
        ];
    }
}
