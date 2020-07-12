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
}
