<?php

namespace App\Api\Requests;

class SocialRegisterRequest extends Request
{
    public function rules()
    {
        return [
            'provider'            => 'required',
            'access_token'        => 'required',
        ];
    }
}
