<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class v2tpdev_pruser extends Model
{
    protected $table = "v2tpdev.PRUSER";
    protected $primaryKey="u_id";
    public $timestamps = false;

    public function Business(){
        return $this->belongsTo(ojdb_business::class,'b_id');
    }

    public function Merchant(){
        return $this->belongsTo(ojdb_merchant::class,'m_id');
    }

    public function flc_user_group(){
        return $this->belongsToMany(v2tpdev_flc_user_group::class,'App\v2tpdev_flc_user_group_mapping','USER_ID','GROUP_ID');
    }

    public function User(){
        return $this->hasMany(User::class,'ojdb_pruser','u_id');
    }
}
