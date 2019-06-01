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
        'xmjlsgrz_sohead_id',
    ];

    public function dtlogitems() {
        return $this->hasMany('App\Models\Dingtalk\Dtlogitem');
    }

    public function xmjlsgrz_sohead() {
        return $this->belongsTo('App\Models\Sales\Salesorder_hxold');
    }
}
