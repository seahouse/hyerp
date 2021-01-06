<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class Reminderswitch extends \App\Models\HxModel
{
    //
//    protected $table = 'reminderswitches';
    protected $old_db = true;
    protected $fillable = [
        'tablename',
        'tableid',
        'type',
        'value',
    ];
}
