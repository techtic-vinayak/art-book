<?php

namespace App\Api\Requests;

class ChangePasswordRequest extends Request
{
    public function rules()
    {
        return [
            'old_password'          => 'required|string|min:6',
            'password'              => 'required|string|min:6',
        ];
    }
}
