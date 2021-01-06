<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;

class Salesorder_hxold_t extends \App\Models\HxModel
{
    //
    protected $table = '订单';
    protected $old_db = true;

    public $timestamps = false;
    public $primaryKey = '订单ID';

    protected $fillable = [
        'purchasereminderactive',
        'associated_remark',
        'othercostpercent',
    ];
}
