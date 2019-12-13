<?php

namespace App\Models\System;

use Illuminate\Foundation\Auth\User as Authenticatable;
// use Zizaco\Entrust\Traits\EntrustUserTrait;
use App\Http\Controllers\Approval\ReimbursementsController;
use App\Http\Controllers\Approval\PaymentrequestsController;
use App\Http\Controllers\DingTalkController;
use Log;

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
//        Log::info($permission);
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

    // 获取“我审批的”报销单
    public function myapproval() {
        return ReimbursementsController::myapproval();
    }

    // 获取“我审批的”付款单
    public function myapproval_paymentrequest() {
        return PaymentrequestsController::myapproval();
    }

    // 获取钉钉的用户信息（远程）
    public function dingtalkGetUser() {
        if (strlen($this->dtuserid) > 0)
            return DingTalkController::userGet($this->dtuserid);
        else
            return null;
    }

    // 获取钉钉的用户信息（本地）
    public function dtuser() {
        return $this->hasOne('App\Models\System\Dtuser');
    }

    public function dtuser2() {
        return $this->hasOne('App\Models\System\Dtuser2');
    }

    // 获取老系统的用户信息
    public function userold() {
        return $this->hasOne('App\Models\System\Userold');
    }
}
