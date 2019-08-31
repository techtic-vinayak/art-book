<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserStatus extends Model
{	
    protected $guarded = ['id'];
    protected $table = 'user_status';
    protected $hidden = ['updated_at', 'created_at'];
}
