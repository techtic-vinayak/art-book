<?php

namespace App\Api\Requests;

class ChangePasswordRequest extends Request
{
    public function rules()
    {
        return [
            'user_id'               => 'required|int|exists:users,id',
            'old_password'          => 'required|string|min:6',
            'password'              => 'required|confirmed|string|min:6',
            'password_confirmation' => 'required|string|min:6',
        ];
    }
}
