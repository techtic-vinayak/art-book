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
            'device_type' => '',
            'token'       => '',
            'timezone'    => '',
            'profile_pic' => 'image|max:2048',
            'phone'       => 'max:20',
            'address'     => 'string|max:150',
            'latitude'    => 'required',
            'longitude'   => 'required',
        ];
    }
}
