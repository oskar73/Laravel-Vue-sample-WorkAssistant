<?php

namespace App\Policies;

use App\Enums\Roles;

class BasePolicies
{
    /**
     * @param $user
     * @param $ability
     *
     * @return bool
     */
    public function before($user, $ability)
    {
        if ($user->hasRole([Roles::ADMIN])) {
            // Do not buy logo for admin
            if ($ability === 'neededPurchasePackage' || $ability === 'neededPurchaseLogo') {
                return false;
            }

            return true;
        }
    }
}
