<?php

namespace App\Models\Dingtalk;

use Illuminate\Database\Eloquent\Model;

class Dtlogitem extends Model
{
    //
    protected $fillable = [
        'dtlog_id',
        'key',
        'value',
        'sort',
        'type',
    ];
}
