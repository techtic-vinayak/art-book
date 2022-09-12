<?php

namespace App\Api\Requests;

class AddArtRequest extends Request
{
    public function rules()
    {
        return [
            'title'         => 'required',
            'image'         => 'required|image|max:2048',
            'category'      => 'required',
            'size'          => 'required|string|max:255',
            'art_gallery'   => 'nullable',
            'material'      => 'nullable|string|max:255',
            'subject'       => 'nullable',
            'about'         => 'nullable',
            'price'         => 'required|numeric'
        ];
    }
}
