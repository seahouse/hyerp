<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;

class Projectsitepurchaseitem extends Model
{
    //
    protected $fillable = [
        'projectsitepurchase_id',
        'item_id',
        'brand',
        'unit_id',
        'quantity',
        'unitprice',
        'price',
        'seq',
    ];
}
