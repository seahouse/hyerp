<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class Dtuser2 extends Model
{
    //
    protected $fillable = [
        'user_id',
        'userid',
        'name',
        'tel',
        'workPlace',
        'remark',
        'mobile',
        'email',
        'orgEmail',
        'active',
        'orderInDepts',
        'isAdmin',
        'isBoss',
        'dingId',
        'isLeaderInDepts',
        'isHide',
        'department',
        'position',
        'avatar',
        'jobnumber',
        'extattr',
    ];

    public function user() {
        return $this->hasOne('App\Models\System\User', 'id', 'user_id');
    }
}
