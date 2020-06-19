<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;

class Additionsalesorderattachment extends Model
{
    //
    protected $fillable = [
        'additionsalesorder_id',
        'type',
        'filename',
        'path',
    ];
}
