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
    protected $appends = ['category_name'];


    public function getPantingSizeAttribute()
    {
        $this->paintingSize();
    }

    public function getCategoryNameAttribute ()
    {

        return $this->categoryData();

    }


    public function setImageAttribute($value)
    {
        $this->saveFile($value, 'image', "image/" . date('Y/m'));
    }

    public function userInfo()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    function paintingSize() {
         return $this->belongsTo(PaintingSize::class, 'size');
    }

    function categoryData() {
        return $this->belongsTo(Category::class,'category','id');
    }

    public function getImageAttribute($value)
    {
        if (!empty($value)) {
            return $this->getFileUrl($value);
        }
    }

}
