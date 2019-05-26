<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class Reminderswitch extends Model
{
    //
//    protected $table = 'reminderswitches';
    protected $connection = 'sqlsrv';
    protected $fillable = [
        'tablename',
        'tableid',
        'type',
        'value',
    ];
}
