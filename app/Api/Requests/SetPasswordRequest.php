<?php

namespace App\Api\Requests;

class SetPasswordRequest extends Request
{
    public function rules()
    {
        return [
            'email'    => 'required|email',
            'password' => 'required|string|min:6|confirmed',
            'token'    => 'required',
        ];
    }
}
