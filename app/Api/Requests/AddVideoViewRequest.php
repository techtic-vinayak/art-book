<?php

namespace App\Api\Requests;

class AddVideoViewRequest extends Request
{
    public function rules()
    {
        return [
            'video_id' => 'required|exists:video,id'
        ];
    }
}
