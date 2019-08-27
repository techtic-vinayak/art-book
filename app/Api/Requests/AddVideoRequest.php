<?php

namespace App\Api\Requests;

class AddVideoRequest extends Request
{
    public function rules()
    {
        return [
            'caption'        => '',
            'thumb_image'    => 'required|image|max:2048',
            'video'          => 'required',
            'video_type'     => 'required|string|max:255',
            'request_type'   => 'required|string|max:255',
            'video_duration' => 'required|string|max:255',
            'contact_id'     => 'exists:users,id',
        ];
    }
}
