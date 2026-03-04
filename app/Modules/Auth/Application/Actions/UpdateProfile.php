<?php

namespace App\Modules\Auth\Application\Actions;

use App\Models\User;

class UpdateProfile
{
    public function execute(User $user, string $name, string $email): void
    {
        $user->fill(['name' => $name, 'email' => $email]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
    }
}
