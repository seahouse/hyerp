<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;

class Issuedrawingtonnagedetail extends Model
{
    //
    protected $fillable = [
        'issuedrawing_id',
        'name',
        'unitprice',
        'tonnage',
    ];
}
