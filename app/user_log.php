<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class user_log extends Model
{
    protected $table = 'user_logs';

    protected $fillable = [
        'user_id','device_id','log_out','start_money','end_money','calculated_end_money','difference'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);


    }
}
