<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Model;

class Poheadquote_hx extends \App\Models\HxModel
{
    //
    protected $table = 'poheadquotes';
    protected $old_db = true;

    protected $fillable = [
        'pohead_id',
        'supplier_id',
        'quote',
        'remark',
    ];

    public function supplier() {
        return $this->belongsTo(Vendinfo_hxold::class);
    }
}
