<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Model;

class Vendinfo_hxold2 extends \App\Models\HxModel
{
    //
    protected $table = 'vsupplier2';
    

    public function vendbanks() {
    	return $this->hasMany('App\Models\Purchase\Vendbank_hxold2', 'vendinfo_id', 'id');
    }
}
