<?php

namespace App\Traits\PxTraits\Policies;

use App\Traits\PxTraits\Policies\Items\SytemUserPolicyTrait;

trait BasePolicyTrait {

    use SytemUserPolicyTrait;
    public function systemPolicies(){
        return [
            [
                'name' => 'Admin Panel',
                'policies' => [
                    [
                        ...$this->systemUserPolicies()
                    ]
                ]
            ]
        ];
    }
}
