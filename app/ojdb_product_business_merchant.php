<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ojdb_product_business_merchant extends Model
{
    protected $table = "oj_db.product_business_merchant";
    protected $primaryKey = "pbm_id";
    public $timestamps = false;

    public function customer_order(){
        return $this->hasMany(ojdb_customer_order::class);
    }

    public function product(){
        return $this->belongsTo(ojdb_product::class,'p_id');
    }

    public function business(){
        return $this->belongsTo(ojdb_business::class,'b_id');
    }

    public function merchant(){
        return $this->belongsTo(ojdb_merchant::class,'m_id');
    }
}
