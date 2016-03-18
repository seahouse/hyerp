<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    //
    protected $fillable = [
        'name',
        'descrip',
        'path',
    ];
}
