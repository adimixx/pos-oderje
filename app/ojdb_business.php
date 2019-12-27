<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ojdb_business extends Model
{
    protected $table = 'oj_db.business';
    protected $primaryKey = 'b_id';

    public function PRUSER()
    {
        return $this->belongsTo(v2tpdev_pruser::class);
    }

    public function Merchant()
    {
        return $this->hasMany(ojdb_merchant::class,'b_id');
    }

    public function User()
    {
        return $this->hasMany(User::class, 'business_id');
    }
}
