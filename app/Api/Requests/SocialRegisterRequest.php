<?php

namespace App\Api\Requests;

class SocialRegisterRequest extends Request
{
    public function rules()
    {
        return [
            'name'            => 'required|string|max:255',
            'email'           => 'string|email|max:255',
            'device_type'     => '',
            'token'           => '',
            'timezone'        => '',
            'profile_pic'     => 'image|max:2048',
            'phone'           => 'max:20',
            'address'         => 'string|max:150',
            'latitude'        => 'required',
            'social_media_type' => 'required',
            'social_media_id'   => 'required|string|max:255',
        ];
    }
}
