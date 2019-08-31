<?php

namespace App\Api\Requests;

class SendRequest extends Request
{
    public function rules()
    {
        return [
            'receiver_id'    => 'required',
        ];
    }
}
