<?php

namespace App\Api\Requests;

class GetPhoneContactsRequest extends Request
{
    public function rules()
    {
        return [
            'contact_numbers' => 'required'
        ];
    }
}
