<?php

namespace App\Models;

use App\Traits\FileUploadTrait;
use Backpack\CRUD\CrudTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use CrudTrait;
    use HasRoles;
    use FileUploadTrait;

    protected $casts = [
        'name'        => 'string',
        'profile_pic' => 'string',
        "device_type" => "string",
        "status"      => "string",
        "token"       => "string",
        "timezone"    => "string",
        "phone"       => "string",
        "address"     => "string",
        "latitude"    => "string",
        "longitude"   => "string",
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    /*protected $fillable = [
    'name', 'email', 'password',
    ];*/

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email_verified_at',
    ];
    public function routeNotificationForFcm($notification)
    {
        return $this->token;
    }

    public function scopeNearBy($query, $latlng, $radius = 100)
    {
        if ($user_id = \Auth::user()->id) {
            $query->where('id', '!=', $user_id);
        }
        if (!empty($latlng['latitude']) && !empty($latlng['longitude'])) {
            $distance = "( 3959 * acos( cos( radians(users.latitude) ) * cos( radians( {$latlng['latitude']} ) ) *
            cos( radians( {$latlng['longitude']} ) - radians(users.longitude) ) + sin( radians(users.latitude) ) *
            sin( radians( {$latlng['latitude']} ) ) ) )";
            $query->whereRaw($distance . "<= " . $radius);
            $query->whereNotNull('latitude');
            $query->whereNotNull('longitude');
        }
    }

    public function getProfilePicAttribute()
    {
        if (!empty($this->attributes['profile_pic'])) {
            return $this->getFileUrl($this->attributes['profile_pic']);
        }
        return "";
    }
    public function setProfilePicAttribute($value)
    {
        $this->saveFile($value, 'profile_pic', 'user');
    }

    public function contacts()
    {
        return $this->belongsToMany(User::class, 'contacts', 'user_id', 'contact_id')->withTimestamps();
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function views()
    {
        return $this->belongsToMany(Video::class, 'video_views');
    }

    public function reports()
    {
        return $this->belongsToMany(Video::class, 'video_reports');
    }
    public function video()
    {
        return $this->hasMany('App\Models\Video')->where('video_type', 'public');
    }
}
