<?php

namespace App\Models\Basic;

use Illuminate\Database\Eloquent\Model;

class Biddinginformationdefinefield extends Model
{
    //
    protected $fillable = [
        'name',
        'sort',
        'type',
        'exceltype',
        'projecttype',
    ];
}
