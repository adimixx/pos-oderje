<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class device extends Model
{
    protected $table = "devices";
    protected $fillable = [
        'business_id', 'merchant_id', 'ip_address','uuid','machine_type','name','status'
    ];
}
