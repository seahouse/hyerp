<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class Operationlog extends Model
{
    //
    protected $fillable = [
        'table_name',
        'table_id',
        'operation',
        'operator_id',
    ];

    public static $ISSUEDRAWING = 'issuedrawings';
}
