<?php

namespace App\Modules\Auth\Interfaces\Http\Controllers;

use App\Modules\Auth\Application\Actions\UpdatePassword;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    public function update(Request $request, UpdatePassword $updatePassword): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $updatePassword->execute($request->user(), $validated['password']);

        return back();
    }
}
