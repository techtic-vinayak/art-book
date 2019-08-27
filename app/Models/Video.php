<?php

namespace App\Models;

use App\Traits\FileUploadTrait;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Video extends Model
{
    use FileUploadTrait;
    use CrudTrait;

    protected $table   = "video";
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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function shareVideo()
    {
        return $this->hasOne(ShareVideo::class);
    }

    public function views()
    {
        return $this->belongsToMany(User::class, 'video_views')->withTimestamps();
    }

    public function reports()
    {
        return $this->belongsToMany(User::class, 'video_reports')->withTimestamps();
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }
}
