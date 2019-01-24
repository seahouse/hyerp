<?php

namespace App\Models\Purchaseorderc;

use Illuminate\Database\Eloquent\Model;

class Asnitem extends Model
{
    //
    protected $fillable = [
        'asn_id',
        'poitemc_id',
        'roll_no',
        'quantity',
    ];

    public function poitemc() {
        return $this->hasOne('\App\Models\Purchaseorderc\Poitemc', 'id', 'poitemc_id');
    }
}
