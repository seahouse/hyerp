<?php

namespace App\Models\Basic;

use Illuminate\Database\Eloquent\Model;

class Biddinginformationitemmodifylog extends Model
{
    //
    protected $fillable = [
        'biddinginformationitem_id',
        'oldvalue',
        'value',
        'isclarify',
    ];
}
