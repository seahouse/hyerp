<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;

class Mcitempurchaseattachment extends Model
{
    //
    protected $fillable = [
        'mcitempurchase_id',
        'type',
        'filename',
        'path',
    ];
}
