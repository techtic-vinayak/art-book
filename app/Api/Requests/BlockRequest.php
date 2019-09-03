<?php

namespace App\Api\Requests;

class BlockRequest extends Request
{
    public function rules()
    {
        return [
            'block_user_id' => 'required|int'
        ];
    }
}