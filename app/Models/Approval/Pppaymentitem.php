<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;

class Pppaymentitem extends Model
{
    //
    protected $fillable = [
        'pppayment_id',
        'sohead_id',
        'productionoverview',
        'tonnage',
        'seq',
    ];
}
