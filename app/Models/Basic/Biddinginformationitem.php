<?php

namespace App\Models\Basic;

use Illuminate\Database\Eloquent\Model;

class Biddinginformationitem extends Model
{
    //
    protected $fillable = [
        'biddinginformation_id',
        'key',
        'value',
        'sort',
        'type',
    ];
}
