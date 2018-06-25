<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;

class Issuedrawingmodifyweightlog extends Model
{
    //
    protected $fillable = [
        'issuedrawing_id',
        'oldtonnage',
        'tonnage',
        'reason',
        'operator_id',
    ];
}
