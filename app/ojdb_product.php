<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ojdb_product extends Model
{
    protected $table="oj_db.product";
    protected $primaryKey = "p_id";
    public $timestamps = false;

    public function pbm(){
        return $this->hasMany(ojdb_product_business_merchant::class);
    }
}
