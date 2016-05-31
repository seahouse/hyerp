<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;

class Reimbursementtravel extends Model
{
    //
    protected $fillable = [
        'reimbursement_id',
        'datego',
        'dateback',
		'customer_id',
		'contacts',
		'contactspost',
		'order_id',
        'descrip',
        'seq',
    ];
	
	public function customer_hxold() {
        return $this->hasOne('\App\Models\Sales\Custinfo_hxold', 'id', 'customer_id');
    }
	
	public function order_hxold() {
        return $this->hasOne('\App\Models\Sales\Salesorder_hxold', 'id', 'order_id');
    }
}
