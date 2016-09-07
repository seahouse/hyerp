<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;

class Paymentrequestapproval extends Model
{
    //
	protected $fillable = [
        'paymentrequest_id',
        'level',
        'approver_id',
        'status',
        'description',
    ];
	
	public function approver() {
        return $this->hasOne('\App\Models\System\User', 'id', 'approver_id');
    }
}
