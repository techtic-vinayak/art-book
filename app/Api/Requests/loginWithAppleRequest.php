<?php

namespace App\Api\Requests;

class loginWithAppleRequest extends Request
{
    public function rules()
    {
        return [
            'apple_id'              => 'required',
        ];
    }
}
