<?php

namespace App\Models\Dingtalk;

use Illuminate\Database\Eloquent\Model;

class Dtlog extends Model
{
    //
    protected $fillable = [
        'report_id',
        'create_time',
        'creator_id',
        'creator_name',
        'dept_name',
        'remark',
        'template_name',
    ];
}
