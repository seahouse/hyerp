<?php

namespace App\Models\Basic;

use Illuminate\Database\Eloquent\Model;

class Factory_hxold extends \App\Models\HxModel
{
    //
    protected $table = 'factory';

    public function company() {
        return $this->belongsTo(Company_hxold::class);
    }
}
