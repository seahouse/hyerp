<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Model;

class Purchaseorder_hx extends Model
{
    //
    protected $table = '采购订单';
	protected $connection = 'sqlsrv';

    protected $fillable = [
        '采购订单编号',
        '对应项目ID',
        '项目名称',
        '采购订单金额',
        '采购订单状态',
        '修造或工程',
        '编号年份',
        '编号数字',
        '编号商品名称',
    ];

}
