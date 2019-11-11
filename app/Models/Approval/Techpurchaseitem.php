<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;

class Techpurchaseitem extends Model
{
    //
    protected $fillable = [
        'techpurchase_id',
        'item_id',
//        'itemunit',
        'quantity',
        'descrip',
//        'seq',
    ];
}
