<?php

namespace App\Api\Requests;
use Illuminate\Validation\Rule;

class PenddingRequest extends Request
{
    public function rules()
    {
        return [
		    'flag'	 => [
		        'required',
		        Rule::in(['sent', 'recevied']),
		    ],
        ];
    }
}
