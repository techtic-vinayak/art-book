<?php

namespace App\Api\Requests;

class AddContactRequest extends Request
{
    public function rules()
    {
        return [
            'contact_id' => 'required|int|exists:users,id'
        ];
    }
}
