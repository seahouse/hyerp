<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;

class Vendordeductionitem extends Model
{
    //
    protected $fillable = [
        'vendordeduction_id',
        'itemname',
        'itemspec',
        'itemunit',
        'quantity',
        'unitprice',
        'seq',
    ];
}
