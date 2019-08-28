<?php

namespace App\Models;

use App\Traits\FileUploadTrait;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Art extends Model
{
    use FileUploadTrait;

    protected $table   = "art";
    protected $guarded = ['id'];

    public function setImageAttribute($value)
    {
        $this->saveFile($value, 'image', "image/" . date('Y/m'));
    }
    
    public function getImageAttribute($value)
    {
        if (!empty($value)) {
            return $this->getFileUrl($value);
        }
    }
    
}
