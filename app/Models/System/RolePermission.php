<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    //
    protected $table = 'permission_role';
    public $timestamps = false;
	public $incrementing = false;
    
    protected $fillable = [
        'permission_id',
        'role_id',
    ];
    
    public function permission() {
        return $this->hasOne('App\Models\System\Permission', 'id', 'permission_id');
    }
    
    public function role() {
        return $this->hasOne('App\Models\System\Role', 'id', 'role_id');
    }
}
