<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ojdb_customer_order extends Model
{
    protected $table = "oj_db.customer_order";
    protected $primaryKey = "co_id";
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->order_date = now();
        $this->order_status = "PAID";
    }

    protected $fillable = ["c_id", "pbm_id", "ppbm_id", "quantity", "order_date", "order_received_date", "order_status", "bill_id"];

    public function linkBill(){
        return $this->belongsTo(ojdb_bill::class,'bill_id');
    }

    public function linkProductBusinessMechant(){
        return $this->belongsTo(ojdb_product_business_merchant::class,'pbm_id');
    }

    public function linkPackageProductBusinessMerchant(){
        return $this->belongsTo(ojdb_package_product_business_merchant::class, 'ppbm_id');
    }
}
