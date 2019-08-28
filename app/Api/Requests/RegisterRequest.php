<?php

namespace App\Api\Requests;

class RegisterRequest extends Request
{
    public function rules()
    {
        return [
            'name'        => 'required|string|max:255',
            'email'       => 'required|string|email|max:255|unique:users',
            'password'    => 'required|string|min:6',
            'role_id'     => 'required',
        ];
    }
}
