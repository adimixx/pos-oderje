<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ojdb_merchant extends Model
{
    protected $table = 'oj_db.merchant';
    protected $primaryKey = 'm_id';

    public function PRUSER(){
        return $this->belongsTo(v2tpdev_pruser::class);
    }

    public function Business(){
        return $this->belongsTo(ojdb_merchant::class,'b_id');
    }

    public function User(){
        return $this->hasMany(User::class,'merchant_id');
    }
}
