<?php

namespace App\Api\Requests;

class UpdateRegisterRequest extends Request
{
    public function rules()
    {
        return [
            'user_id'     => 'required|int|exists:users,id',
            'name'        => 'required|string|max:255',
            'device_type' => '',
            'token'       => '',
            'timezone'    => '',
            'profile_pic' => 'image|max:2048',
            'phone'       => 'max:20',
            'address'     => 'max:150',
            'latitude'    => 'required',
            'longitude'   => 'required',
        ];
    }
}
