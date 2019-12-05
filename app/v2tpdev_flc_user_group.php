<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class v2tpdev_flc_user_group extends Model
{
    protected $table = "v2tpdev.FLC_USER_GROUP";
    protected $primaryKey="GROUP_ID";
    public $timestamps = false;
}
