<?php

namespace App\Traits\PxTraits\Policies\Items;

trait SytemUserPolicyTrait {

    public function systemUserPolicies(){
        return [
            'name' => 'System Core',
            'policies' => [
                [
                    'name' => 'System User Roles',
                    'keys' => ['view','store','bulk_update','delete','pdf','excel','edit']
                ],
                [
                    'name' => 'System User',
                    'keys' => ['view','store','bulk_update','delete','pdf','excel','edit','pass_change']
                ],
                [
                    'name' => 'System User Policies',
                    'keys' => ['view','edit']
                ]
            ]
        ];
    }
}
