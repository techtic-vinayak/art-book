<?php

namespace App\Api\Requests;

class ListReplyRequest extends Request
{
    public function rules()
    {
        return [
            'video_id' => 'required|int'
        ];
    }
}
