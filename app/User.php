<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password', 'name','business_id', 'merchant_id','created_by','ojdb_PRUSER','status','api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function merchant()
    {
        return $this->belongsTo(ojdb_merchant::class, 'merchant_id');
    }

    public function business()
    {
        return $this->belongsTo(ojdb_business::class, 'business_id');
    }

    public function pruser(){
        return $this->belongsTo(v2tpdev_pruser::class,'ojdb_pruser','u_id');
    }

    public function collection(){
        return $this->hasMany(collection::class,'user_id');
    }

    public function userlog(){
        return $this->hasMany(user_log::class,'user_id');
    }
}
