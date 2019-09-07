<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Art;
use Illuminate\Notifications\Notifiable;

class Connection extends Model
{
	use SoftDeletes;
    use Notifiable;
	
    protected $guarded = ['id'];
    protected $hidden = ['updated_at', 'created_at', 'deleted_at'];

    public function followingUser()
    {
    	return $this->belongsTo(User::class,'receiver_id');
    }

    
    public function followerUser()
    {
    	return $this->belongsTo(User::class,'sender_id');
    }

}
