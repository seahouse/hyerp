<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Model;

class Vendinfo_hxold extends \App\Models\HxModel
{
    //
    protected $table = 'vsupplier';
    protected $old_db = true;

    public function vendbanks() {
    	return $this->hasMany('App\Models\Purchase\Vendbank_hxold', 'vendinfo_id', 'id');
    }
}
