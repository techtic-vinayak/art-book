<?php

namespace App\Api\Requests;

class ForgetPasswordRequest extends Request
{
    public function rules()
    {
        return [
            'email' => 'required|string|email|max:50',
        ];
    }
}
