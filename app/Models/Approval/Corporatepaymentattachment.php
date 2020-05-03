<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;

class Corporatepaymentattachment extends Model
{
    //
    protected $fillable = [
        'corporatepayment_id',
        'type',
        'filename',
        'path',
    ];
}
