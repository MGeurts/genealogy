<?php

namespace App\Http\Responses;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request)
    {
        return redirect(session('link'));
    }
}
