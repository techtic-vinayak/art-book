<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Connection extends Model
{
	use SoftDeletes;
	
    protected $guarded = ['id'];
    protected $hidden = ['updated_at', 'created_at', 'deleted_at'];
}
