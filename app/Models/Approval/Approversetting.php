<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;

class Approversetting extends Model
{
    //
    protected $fillable = [
        'approvaltype_id',
        'approver_id',
        'dept_id',
        'position',
        'level',
        'descrip',
    ];

    public function approvaltype() {
    	return $this->hasOne('\App\Models\Approval\Approvaltype', 'id', 'approvaltype_id');
    }

    public function approver() {
    	return $this->hasOne('\App\Models\System\User', 'id', 'approver_id');
    }

    public function dept() {
        return $this->hasOne('\App\Models\System\Dept', 'id', 'dept_id');
    }
}
