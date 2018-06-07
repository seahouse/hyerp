<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;

class Pppaymentitemunitprice extends Model
{
    //
    protected $fillable = [
        'pppaymentitem_id',
        'name',
        'unitprice',
        'tonnage',
    ];
}
