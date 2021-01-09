<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Model;

class Vendbank_hxold extends \App\Models\HxModel
{
    //
    protected $table = 'vendbanks';
//    protected $connection = 'sqlsrv';
    
    // protected $dates = ['created_at', 'updated_at'];
    // protected $dateFormat = 'yyyy-mm-dd hh:mm:ss.000';

    protected $fillable = [
        'vendinfo_id',
        'bankname',
        'accountnum',
        'descrip',
        'isdefault',
    ];
}
