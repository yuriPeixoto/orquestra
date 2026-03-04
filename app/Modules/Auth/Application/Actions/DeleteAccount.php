<?php

namespace App\Modules\Auth\Application\Actions;

use App\Models\User;

class DeleteAccount
{
    public function execute(User $user): void
    {
        $user->delete();
    }
}
