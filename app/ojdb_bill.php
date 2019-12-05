<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ojdb_bill extends Model
{
    protected $table = 'oj_db.bill';
    protected $primaryKey = 'bill_id';
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $Nowdate = now();
        $this->bill_date = $Nowdate;
        $this->bill_reference = "OJ" . str_replace(" ","", str_replace(":","",str_replace("-","",(string)$Nowdate)));

        $this->save();
    }

    public function linkCustomerOrder(){
        return $this->hasMany(ojdb_customer_order::class);
    }

    public function collection(){
        return $this->hasMany(collection::class,'bill_id','bill_id');
    }

    public function transaction()
    {
        return $this->hasOne(ojdb_transaction::class,'bill_id');
    }
}
