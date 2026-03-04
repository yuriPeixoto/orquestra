<?php

namespace App\Modules\Auth\Application\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdatePassword
{
    public function execute(User $user, string $password): void
    {
        $user->update(['password' => Hash::make($password)]);
    }
}
