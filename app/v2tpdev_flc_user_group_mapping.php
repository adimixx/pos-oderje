<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class v2tpdev_flc_user_group_mapping extends Model
{
    protected $table = "v2tpdev.FLC_USER_GROUP_MAPPING";
    protected $primaryKey=["GROUP_ID","USER_ID"];
    public $timestamps = false;

    public function pruser(){
        return $this->belongsTo(v2tpdev_pruser::class);
    }

    public function flc_user_group(){
        return $this->belongsTo(v2tpdev_flc_user_group::class);
    }
}
