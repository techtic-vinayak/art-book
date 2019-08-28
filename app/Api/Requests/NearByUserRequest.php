<?php

namespace App\Api\Requests;

class NearByUserRequest extends Request
{
    public function rules()
    {
        return [
            'latitude'  => 'required|string',
            'longitude' => 'required|string',
        ];
    }
}
