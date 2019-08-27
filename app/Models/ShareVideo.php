<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShareVideo extends Model
{
    protected $table   = "share_videos";
    protected $guarded = ['id'];

    public function shareVideo()
    {
        return $this->belongsTo('App\Models\Video', 'video_id', 'id')->select('*');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id', 'id');
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id', 'id');
    }
}
