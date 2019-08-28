<?php

namespace App\Api\Requests;

class LoginRequest extends Request
{
    public function rules()
    {
        return [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
            'device_type' => '',
            'token' => '',
            'timezone' => '',
        ];
    }
}
