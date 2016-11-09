<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;

use App\Models\System\User;
use App\Models\System\Dept;

class Paymentrequest extends Model
{
    //
    protected $fillable = [
        'suppliertype',
        'paymenttype',
        'supplier_id',
        'pohead_id',
        'equipmentname',
        'descrip',
        'amount',
        'paymentmethod',
        'datepay',
        'vendbank_id',
        'bank',
        'bankaccountnumber',
        'applicant_id',
		'status',
		'approversetting_id',
    ];

    public function supplier_hxold() {
        return $this->hasOne('\App\Models\Purchase\Vendinfo_hxold', 'id', 'supplier_id');
    }

    public function purchaseorder_hxold() {
        return $this->hasOne('\App\Models\Purchase\Purchaseorder_hxold', 'id', 'pohead_id');
    }

    public function vendbank_hxold() {
        return $this->hasOne('\App\Models\Purchase\Vendbank_hxold', 'id', 'vendbank_id');
    }

    public function applicant() {
        return $this->hasOne('\App\Models\System\User', 'id', 'applicant_id');
    }

    public function paymentrequestapprovals() {
        return $this->hasMany('\App\Models\Approval\Paymentrequestapproval', 'paymentrequest_id', 'id');
    }

    public function paymentrequestattachments() {
        return $this->hasMany('\App\Models\Approval\Paymentrequestattachment', 'paymentrequest_id', 'id');
    }

    public function paymentnodes() {
        return $this->paymentrequestattachments->where('type', 'paymentnode');
    }

    public function businesscontracts() {
        return $this->paymentrequestattachments->where('type', 'businesscontract');
    }

    public function paymentrequestimages() {
        return $this->paymentrequestattachments->where('type', 'image');
    }
	
	public function nextapprover() {
        // $userid = Auth::user()->id;
        $user = null;
        $approversetting = Approversetting::find($this::getAttribute('approversetting_id'));
        if ($approversetting)
        {
			if ($approversetting->approver_id > 0)
			{
				$user = User::where('id', $approversetting->approver_id)->first();
			}
			else
			{
				if ($approversetting->dept_id > 0 && strlen($approversetting->position) > 0)    // 设置了部门与职位才进行查找
				{
					$user = User::where('dept_id', $approversetting->dept_id)->where('position', $approversetting->position)->first();
					// $username = $user->name; 
				}
			}

        }

        return $user;
    }
}
