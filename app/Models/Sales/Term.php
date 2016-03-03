<?php

namespace App\Sales;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    //
    protected $fillable = [
        'code',
        'descrip',
        'duedays',
        'discdays',
        'discprcnt',
    ];
}
