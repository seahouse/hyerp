<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;

class Pppaymentitemattachment extends Model
{
    //
    protected $fillable = [
        'pppaymentitem_id',
        'type',
        'filename',
        'path',
    ];
}
