<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class device extends Model
{
    protected $table = "devices";
    protected $fillable = [
        'business_id', 'merchant_id', 'uuid','machine_type','name','status'
    ];
}
