<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;

class Epcseceninghumanday extends Model
{
    //
    protected $fillable = [
        'epcsecening_id',
        'humandays_type',
        'humandays',
        'humandays_unitprice',
        'remark',
    ];
}
