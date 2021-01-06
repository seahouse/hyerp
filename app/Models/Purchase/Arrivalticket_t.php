<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Model;

class Arrivalticket_t extends \App\Models\HxModel
{
    //
    protected $table = '到票明细';
    protected $old_db = true;
    public $timestamps = false;

    protected $fillable = [
        '到票ID',
        '所属采购订单ID',
        '到票类别',
        '到票抬头',
        '到票日期',
        '到票金额',
        '发票号码',
        '收票人ID',
        '到票说明',
        'type',
    ];
}
