<?php

namespace App\Models\System;

use Illuminate\Foundation\Auth\User as Authenticatable;
// use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    // use EntrustUserTrait; // add this trait to your user model

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'dtuserid', 'dept_id', 'position'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    
    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }
     
        return !! $role->intersect($this->roles)->count();
    }

    public function hasPermission($permission)
    {
        return $this->hasRole($permission->roles);
    }

    public function isSuperAdmin()
    {
        // 系统初始的第一个用户为超级管理员
        if ($this->email == 'admin@admin.com')
            return true;

        return $this->hasRole('superadministrator');
    }

    public function dept() {
        return $this->hasOne('App\Models\System\Dept', 'id', 'dept_id');
    }
}
