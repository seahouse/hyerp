<?php

namespace App\System;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    //
    protected $table = 'permission_role';
    public $timestamps = false;
    
    protected $fillable = [
        'permission_id',
        'role_id',
    ];
    
    public function permission() {
        return $this->hasOne('App\Permission', 'id', 'permission_id');
    }
    
    public function role() {
        return $this->hasOne('App\Role', 'id', 'role_id');
    }
}
