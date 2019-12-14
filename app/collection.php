<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class collection extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    protected $fillable = [
        'transaction_id','device_id'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function bill(){
        return $this->belongsTo(ojdb_bill::class, 'bill_id');
    }
}
