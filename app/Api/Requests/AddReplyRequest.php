<?php

namespace App\Api\Requests;

class AddReplyRequest extends Request
{
    public function rules()
    {
        return [
            'caption'        => '',
            'thumb_image'    => 'required|image|max:2048',
            'video_type'     => 'required|string|max:255',
            'video'          => 'required',
            'video_duration' => 'required|string|max:255',
            'video_id'       => 'required|string|max:255',
            'to_user_id'     => 'required|exists:users,id',
        ];
    }
}
