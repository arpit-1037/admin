<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse;

class CustomLoginResponse implements LoginResponse
{
    public function toResponse($request)
    {
        $user = $request->user();

        if ($user->role === 'admin') {
            return redirect()->intended('/admin/dashboard')->with('welcome_message', 'Welcome, Admin sir!');
        }
        return redirect()->intended('/user/dashboard')->with('welcome_message', 'Welcome back, ' . $user->name . '!');
    }
}
