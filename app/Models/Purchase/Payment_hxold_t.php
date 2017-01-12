<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Model;

class Payment_hxold_t extends Model
{
    //
    protected $table = '付款明细';
	protected $connection = 'sqlsrv';
	public $timestamps = false;

	protected $fillable = [
        '所属采购订单ID',
        '供应商名称',
        '付款日期',
        '付款金额',
        '付款经办人ID',
        '付款说明',
        '录入时间',
    ];
}
