<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;

class Pppaymentitemissuedrawing extends Model
{
    //
    protected $fillable = [
        'pppaymentitem_id',
        'issuedrawing_id',
    ];
}
