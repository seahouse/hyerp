<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class Userrole extends Model
{
    protected $table = 'role_user';
    public $timestamps = false;
    public $incrementing = false;
    
    //
    protected $fillable = [
        'user_id',
        'role_id',
    ];    
   
    public function user() {
        return $this->hasOne('App\Models\System\User', 'id', 'user_id');
    }
    
    public function role() {
        return $this->hasOne('App\Models\System\Role', 'id', 'role_id');
    }
}
