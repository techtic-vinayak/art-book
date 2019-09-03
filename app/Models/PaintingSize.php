<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaintingSize extends Model
{
    protected $table   = "panting_sizes";
    protected $guarded = ['id'];
     protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
}
