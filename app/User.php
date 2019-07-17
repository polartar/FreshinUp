<?php

namespace App;

class User extends \FreshinUp\FreshBusForms\Models\User\User
{
    public function getMorphClass()
    {
        return 'FreshinUp\FreshBusForms\Models\User\User';
    }
}
