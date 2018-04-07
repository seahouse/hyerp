<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;

class Mcitempurchaseissuedrawing extends Model
{
    //
    protected $fillable = [
        'mcitempurchase_id',
        'issuedrawing_id',
    ];
}
