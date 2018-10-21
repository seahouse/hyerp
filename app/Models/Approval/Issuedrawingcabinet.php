<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;

class Issuedrawingcabinet extends Model
{
    //
    protected $fillable = [
        'issuedrawing_id',
        'name',
        'quantity',
    ];
}
