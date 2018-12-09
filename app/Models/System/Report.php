<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    //
    protected $fillable = [
        'name',
        'module',
        'titleshow',
        'active',
        'autostatistics',
        'sumcol',
        'descrip',
        'statement'
    ];
}
