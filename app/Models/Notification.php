<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table   = "notifications";
    protected $guarded = ['id'];
    protected $hidden = ['type','notifiable_type','notifiable_id'];
    protected $casts  = ['data'=> 'array'];

    //key type for primary key
    //default it will return int
    protected $keyType = 'string';
    public $incrementing = false;
}
