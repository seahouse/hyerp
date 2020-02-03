<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class Salarysheetreply extends Model
{
    //
    protected $fillable = [
        'salarysheet_id',
        'status',
        'message',
    ];
}
