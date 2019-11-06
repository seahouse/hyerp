<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class Sitehoursreminderrecords extends Model
{
    //
    protected $table = 'sitehoursreminderrecords';
    protected $fillable = [
        'sohead_id',
        'humandays',
        'senddate',
    ];
}
