<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse;

class CustomRegisterResponse implements RegisterResponse
{
    public function toResponse($request)
    {
        // dd('Custom Register Response Triggered');   
        return redirect()
            ->intended('/user/dashboard')
            ->with('Welcome_new_user', 'Explore our latest products!');
    }
}
