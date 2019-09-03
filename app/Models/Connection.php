<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Connection extends Model
{
	use SoftDeletes;
	
    protected $guarded = ['id'];
    protected $hidden = ['updated_at', 'created_at', 'deleted_at'];

    public function user()
    {
    	return $this->belongsTo(User::class,'receiver_id');
    }
}
