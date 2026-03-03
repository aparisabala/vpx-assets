<?php

namespace App\Traits\PxTraits\Policies;

use App\Traits\PxTraits\Policies\Items\HrmUserPolicyTrait;

trait BasePolicyTrait {

    use HrmUserPolicyTrait;
    public function hrmPolicies(){
        return [
            [
                'name' => 'Admin Panel',
                'policies' => [
                    [
                        ...$this->hrmUserPolicies()
                    ]
                ]
            ]
        ];
    }
}
