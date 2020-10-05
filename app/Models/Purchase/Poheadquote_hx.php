<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Model;

class Poheadquote_hx extends Model
{
    //
    protected $table = 'poheadquotes';
    protected $connection = 'sqlsrv';

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
