<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;

class Issuedrawingattachment extends Model
{
    //
    protected $fillable = [
        'issuedrawing_id',
        'type',
        'filename',
        'path',
    ];
}
