<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class device extends Model
{
    protected $table = "devices";
    protected $fillable = [
        'business_id', 'merchant_id', 'uuid','machine_type','name','status'
    ];

    public function collection(){
        return $this->hasMany(collection::class,'device_id');
    }

    public function user_log(){
        return $this->hasMany(user_log::class,'device_id');
    }
}

