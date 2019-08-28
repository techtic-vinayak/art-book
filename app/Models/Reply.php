<?php

namespace App\Models;

use App\Traits\FileUploadTrait;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use FileUploadTrait;

    protected $table   = "replies";
    protected $guarded = ['id'];

    public function getVideoAttribute()
    {
        if (!empty($this->attributes['video'])) {
            return $this->getFileUrl($this->attributes['video']);
        }
        return "";
    }

    public function setVideoAttribute($value)
    {
        $this->saveFile($value, 'video', 'user');
    }

    public function getThumbImageAttribute()
    {
        if (!empty($this->attributes['thumb_image'])) {
            return $this->getFileUrl($this->attributes['thumb_image']);
        }
        return "";
    }

    public function setThumbImageAttribute($value)
    {
        $this->saveFile($value, 'thumb_image', 'user');
    }

    public function userInfo()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id')->select('name', 'id', 'profile_pic');
    }
}
