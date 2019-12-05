<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class user_log extends Model
{
    protected $table = 'user_logs';

    protected $fillable = [
        'user_id','device_id','log_out'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);


    }
}
