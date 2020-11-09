<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;

class Epcseceningmaterial extends Model
{
    //
    protected $fillable = [
        'epcsecening_id',
        'material_type',
        'item_id',
        'quantity',
        'unitprice',
        'remark',
    ];
}
