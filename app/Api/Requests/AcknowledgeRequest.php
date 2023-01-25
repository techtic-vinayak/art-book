<?php

namespace App\Api\Requests;
use Illuminate\Validation\Rule;

class AcknowledgeRequest extends Request
{
    public function rules()
    {
        return [
            'request_id' => 'required|exists:connections,id',
		    'status'	 => [
		        'required',
		        Rule::in(['accepted', 'rejected', 'cancel']),
		    ],
            
        ];
    }
}
