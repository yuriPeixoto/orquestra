<?php

namespace App\Modules\Auth\Interfaces\Http\Controllers;

use App\Models\User;
use App\Modules\Auth\Application\Actions\RegisterUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    public function store(Request $request, RegisterUser $registerUser): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = $registerUser->execute(
            $request->string('name'),
            $request->string('email'),
            $request->string('password'),
        );

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
