<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;

use App\Models\System\User;
use App\Models\System\Dept;

class Reimbursement extends Model
{
    //
    protected $fillable = [
        'reimbursementtype_id',
        'date',
        'number',
        'amount',
        'customer_id',
        'contacts',
        'contactspost',
        'order_id',
        'status',
        'statusdescrip',
        'descrip',
        'datego',
        'dateback',
        'mealamount',
        'ticketamount',
        'amountAirfares',
        'amountTrain',
        'amountTaxi',
        'amountOtherTicket',
        'stayamount',
        'otheramount',
        'approvaler1_id',
        'approvaldate1',
        'approvaler2_id',
        'approvaldate2',
        'approvaler3_id',
        'approvaldate3',
        'applicant_id',
        'approversetting_id',
    ];

    public function order() {
        return $this->hasOne('\App\Models\Sales\Salesorder', 'id', 'order_id');
    }

    public function applicant() {
        return $this->hasOne('\App\Models\System\User', 'id', 'applicant_id');
    }

    public function reimbursementimages() {
        return $this->hasMany('\App\Models\Approval\Reimbursementimages', 'reimbursement_id', 'id');
    }

    public function customer_hxold() {
        return $this->hasOne('\App\Models\Sales\Custinfo_hxold', 'id', 'customer_id');
    }

    public function order_hxold() {
        return $this->hasOne('\App\Models\Sales\Salesorder_hxold', 'id', 'order_id');
    }

    public function approversetting() {
        return $this->hasOne('\App\Models\Approval\Approversetting', 'id', 'approversetting_id');
    }

    public function reimbursementtravels() {
        return $this->hasMany('\App\Models\Approval\Reimbursementtravel', 'reimbursement_id', 'id');
    }

    public function reimbursementapprovals() {
        return $this->hasMany('\App\Models\Approval\Reimbursementapprovals', 'reimbursement_id', 'id');
    }

    public function nextapprover() {
        // $userid = Auth::user()->id;
        $user = null;
        $approversetting = Approversetting::find($this::getAttribute('approversetting_id'));
        if ($approversetting)
        {
            if ($approversetting->dept_id > 0 && strlen($approversetting->position) > 0)    // 设置了部门与职位才进行查找
            {
                $user = User::where('dept_id', $approversetting->dept_id)->where('position', $approversetting->position)->first();
                // $username = $user->name; 
            }
            elseif ($approversetting->level == 2)       // 第二层没有设置部门与职位，则找出部门经理的人来匹配当前用户
            {
                $applicant = User::find($this::getAttribute('applicant_id'));                
                if ($applicant)
                {
                    $dept = Dept::find($applicant->dept_id);
                    if ($dept)
                    {
                        $user = User::where('dept_id', $dept->id)->where('position', $approversetting->position)->first();
                    }
                }
            }
            elseif ($approversetting->level == 3) {     // 第三层没有设置部门与职位，则根据实际情况来确定哪个副总
                $applicant = User::find($this::getAttribute('applicant_id'));                
                if ($applicant)
                {
                    $dept = Dept::find($applicant->dept_id);
                    if ($dept)
                    {
                        $level3s = config('custom.dingtalk.approversettings.reimbursement.level3');
                        $b = false;
                        foreach ($level3s as $key => $level3) {
                            if ($b) break;

                            foreach ($level3 as $deptname) {
                                if ($deptname == $dept->name)
                                {
                                    $user = User::where('dtuserid', $key)->first();
                                    $b = true;
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }

        return $user;
    }
}
