<?php

namespace App\Models\Dingtalk;

use Illuminate\Database\Eloquent\Model;

class Dtlogcomment extends Model
{
    //
    protected $fillable = [
        'dtlog_id',
        'content',
        'create_time',
        'userid',
    ];

    public function user() {
        return $this->hasOne('App\Models\System\User', 'dtuserid', 'userid');
    }
}
