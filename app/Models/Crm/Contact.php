<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    //
    protected $fillable = [
        'name',
        'addr_id',
        'phone',
        'phone2',
        'fax',
        'email',
    ];
    
    public function addr() {
        return $this->hasOne('App\Addr', 'id', 'addr_id');
    }
}
