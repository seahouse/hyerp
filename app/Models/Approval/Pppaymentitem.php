<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;

class Pppaymentitem extends Model
{
    //
    protected $fillable = [
        'pppayment_id',
        'sohead_id',
        'productionoverview',
        'tonnage',
        'area',
        'type',
        'seq',
    ];

    public function pppaymentitemunitprices()
    {
        return $this->hasMany('\App\Models\Approval\Pppaymentitemunitprice', 'pppaymentitem_id', 'id');
    }

    public function pppayment() {
        return $this->hasOne('\App\Models\Approval\Pppayment', 'id', 'pppayment_id');
    }
}
