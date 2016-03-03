<?php

namespace App\System;

use Illuminate\Database\Eloquent\Model;

class Userrole extends Model
{
    protected $table = 'role_user';
    public $timestamps = false;
    //
    protected $fillable = [
        'user_id',
        'role_id',
    ];    
   
    public function user() {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
    
    public function role() {
        return $this->hasOne('App\Role', 'id', 'role_id');
    }
}
