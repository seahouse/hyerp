<?php

namespace App\Product;

use Illuminate\Database\Eloquent\Model;

class Characteristic extends Model
{
    //
    protected  $table = 'chars';
    
    protected $fillable = [
        'name',
        'bitems',
        'descrip',
    ];
}
