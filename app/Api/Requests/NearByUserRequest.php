<?php

namespace App\Api\Requests;

class NearByUserRequest extends Request
{
    public function rules()
    {
        return [
            'latitude'  => 'sometimes|required|string',
            'longitude' => 'sometimes|required|string',
        ];
    }
}
