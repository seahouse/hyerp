<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;

class Epcseceningattachment extends Model
{
    //
    protected $fillable = [
        'epcsecening_id',
        'type',
        'filename',
        'path',
    ];
}
