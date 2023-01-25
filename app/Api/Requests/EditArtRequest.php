<?php

namespace App\Api\Requests;

class EditArtRequest extends Request
{
    public function rules()
    {
        return [
        	'id'			=> 'required',
            'title'         => 'nullable',
            'image'         => 'nullable|image|max:2048',
            'category'      => 'nullable',
            'size'          => 'nullable|string|max:255',
            'art_gallery'   => 'nullable',
            'material'      => 'nullable|string|max:255',
            'subject'       => 'nullable',
            'about'         => 'nullable',
            'price'         => 'required'
        ];
    }

}
