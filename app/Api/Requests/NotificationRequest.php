<?php

namespace App\Api\Requests;

class NotificationRequest extends Request
{
    public function rules()
    {
        return [
            'notification_id' => 'sometimes|required|exists:notifications,id',
        ];
    }
}