<?php

namespace App\Api\Requests;

class AddVideoReportRequest extends Request
{
    public function rules()
    {
        return [
            'video_id' => 'required|exists:video,id'
        ];
    }
}
