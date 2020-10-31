<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;

class Customerdeductionattachment extends Model
{
    //
    protected $fillable = [
        'customerdeduction_id',
        'type',
        'filename',
        'path',
    ];
}
