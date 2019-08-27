<?php

namespace App\Api\Requests;

class VideoListRequest extends Request
{
    public function rules()
    {
        return [
            'type' => '',
            'user_id'=> 'exists:users,id'
        ];
    }
}
