<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ojdb_transaction extends Model
{
    protected $table = 'oj_db.transaction';
    protected $primaryKey = 't_id';
    public $timestamps = false;

    protected $fillable = [
      "type","vt_id","sender","receiver","amount","status","bill_id","type_acc","voucher_id"
    ];

    public function bill(){
        return $this->belongsTo(ojdb_bill::class, 'bill_id');
    }
}
