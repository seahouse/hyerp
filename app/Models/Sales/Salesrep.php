<?php

namespace App\Sales;

use Illuminate\Database\Eloquent\Model;

class Salesrep extends Model
{
    //
    protected $fillable = [
        'active',
        'number',
        'name',
        'commission',
        'employee_id',
    ];
    
    public function employee() {
        return $this->hasOne('App\System\Employee', 'id');
    }
}
