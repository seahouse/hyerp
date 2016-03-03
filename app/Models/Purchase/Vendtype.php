<?php

namespace App\Purchase;

use Illuminate\Database\Eloquent\Model;

class Vendtype extends Model
{
    //
    protected $fillable = [
        'code',
        'descrip',
    ];
}
