<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    /**
     * After registration, redirect to the email verification notice page
     * instead of the dashboard.
     */
    public function toResponse($request)
    {
        return redirect()->route('verification.notice');
    }
}
