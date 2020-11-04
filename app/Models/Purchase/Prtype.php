<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Model;

class Prtype extends Model
{
    //
    protected $fillable = [
        'prhead_id',
        'supplier_id',
        'quoteamount',
    ];

    public function prhead() {
        return $this->belongsTo(Prhead::class, 'prhead_id');
    }

    public function supplier() {
        return $this->belongsTo(Vendinfo_hxold::class, 'supplier_id');
    }
}
