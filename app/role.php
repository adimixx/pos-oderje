<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class role extends Model
{
    public $timestamps = true;
    protected $fillable=['name'];


    public function Users(){
        return $this->belongsToMany(User::Class, 'user_roles','role_id','user_id')->withTimestamps();
    }
}
