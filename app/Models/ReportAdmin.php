<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class ReportAdmin extends Model
{
	use CrudTrait;

    protected $table   = "report_admin";
    protected $guarded = ['id'];
    // protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function art()
    {
        return $this->belongsTo('App\Models\Art', 'art_id', 'id');
    }
}
