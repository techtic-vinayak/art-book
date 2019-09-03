<?php

namespace App\Models;

use App\Traits\FileUploadTrait;
use Backpack\CRUD\CrudTrait;
use App\Models\LinkedSocialAccount;
use App\Models\UserStatus;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\Connection;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use CrudTrait;
    use HasRoles;
    use FileUploadTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    
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
    
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function setProfilePicAttribute($value)
    {
        $this->saveFile($value, 'profile_pic', "user/" . date('Y/m'));
    }
    
    public function getProfilePicAttribute($value)
    {
        if (!empty($value)) {
            return $this->getFileUrl($value);
        }
    }

    public function setCoverImgAttribute($value)
    {
        $this->saveFile($value, 'cover_img', "user/" . date('Y/m'));
    }
    
    public function getCoverImgAttribute($value)
    {
        if (!empty($value)) {
            return $this->getFileUrl($value);
        }
    }

    public function linkedSocialAccounts()
    {
        return $this->hasMany(LinkedSocialAccount::class);
    }


    public function following()
    {
        return $this->hasMany(Connection::class,'sender_id')->where('status','accepted');
    }

    public function follower()
    {
        return $this->hasMany(Connection::class,'receiver_id')->where('status','accepted');
    }


    public function blockUsers()
    {
        return $this->belongsToMany(User::class, 'user_status', 'user_id', 'block_user_id');

    }
}
