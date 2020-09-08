<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\System\User;
use App\Models\System\Dept;

class Paymentrequest extends Model
{
    //
    use SoftDeletes;
    protected $dates = ['deleted_at'];

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
        'paymentmethod2',
        'vendbank2_id',
        'bank',
        'bankaccountnumber',
        'applicant_id',
		'status',
		'approversetting_id',
        'associated_approval_type',
        'associated_process_instance_id',
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

    public function vendbank_hxold2() {
        return $this->hasOne('\App\Models\Purchase\Vendbank_hxold', 'id', 'vendbank2_id');
    }

    public function applicant() {
        return $this->hasOne('\App\Models\System\User', 'id', 'applicant_id');
    }

    public function approversetting() {
        return $this->hasOne('\App\Models\Approval\Approversetting', 'id', 'approversetting_id');
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

    public function paymentrequestretract() {
        return $this->hasOne('\App\Models\Approval\Paymentrequestretract', 'paymentrequest_id', 'id');
    }

    public function associated_business_id() {
        $business_id = '';
        if ($this::getAttribute('associated_approval_type') == 'corporatepayment')
        {
            $item = Corporatepayment::where('process_instance_id', $this::getAttribute('associated_process_instance_id'))->first();
            if (isset($item))
                $business_id = $item->business_id;
        }
        return $business_id;
    }
	
	public function nextapprover() {
        // $userid = Auth::user()->id;
        $user = null;
        $approversetting = Approversetting::find($this::getAttribute('approversetting_id'));
        if ($approversetting)
        {
            // 如果是河南华星，第5层为候S
            // 如果是中易新材料或者中易电力，第5层设置为侯S
            if ($approversetting->level == 5 && isset($this->purchaseorder_hxold->purchasecompany_id) &&
                ($this->purchaseorder_hxold->purchasecompany_id == 3 || $this->purchaseorder_hxold->purchasecompany_id == 2 || $this->purchaseorder_hxold->purchasecompany_id == 4))
            {
                $user = User::where('id', 123)->first();
                return $user;
            }

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
