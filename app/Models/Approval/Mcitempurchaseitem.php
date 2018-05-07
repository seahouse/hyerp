<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;

class Mcitempurchaseitem extends Model
{
    //
    protected $fillable = [
        'mcitempurchase_id',
        'item_id',
        'size',
        'unitprice',
        'quantity',
        'weight',
        'remark',
        'seq',
    ];
}