<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;

class Additionsalesorderitem extends Model
{
    //
    protected $fillable = [
        'additionsalesorder_id',
        'type',
        'otherremark',
        'unit',
        'quantity',
        'amount',
    ];
}
