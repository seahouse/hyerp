<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;

class Epcseceningcrane extends Model
{
    //
    protected $fillable = [
        'epcsecening_id',
        'crane_type',
        'number',
        'unitprice',
    ];
}
