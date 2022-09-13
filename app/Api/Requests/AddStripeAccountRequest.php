<?php

namespace App\Api\Requests;

//use App\Api\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class AddStripeAccountRequest extends FormRequest
{
    public function rules()
    {
        return [
            // "user_id" => "nullable|numeric",
            "code"    => "required",
        ];
    }
}

?>