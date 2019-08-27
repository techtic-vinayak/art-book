<?php

namespace App\Api\Requests;

class DeleteVideoRequest extends Request
{
    public function rules()
    {
        return [
            'video_id' => 'required|int',
            'type'     => '',
        ];
    }
}
