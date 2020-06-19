<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;

class Additionsalesorder extends Model
{
    //
    protected $fillable = [
        'sohead_id',
        'signcontract_condition',
        'reason',
        'remark',
        'applicant_id',
        'status',
        'process_instance_id',
        'business_id',
    ];

    public function additionsalesorderitems() {
        return $this->hasMany('\App\Models\Approval\Additionsalesorderitem');
    }
}
