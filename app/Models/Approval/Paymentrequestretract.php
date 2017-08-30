<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;
use App\Models\System\User;
use Log;

class Paymentrequestretract extends Model
{
    //
    protected $fillable = [
        'paymentrequest_id',
        'retractreason',
        'applicant_id',
        'status',
        'approversetting_id',
    ];

    public function applicant() {
        return $this->hasOne('\App\Models\System\User', 'id', 'applicant_id');
    }

    public function paymentrequest() {
        return $this->hasOne('\App\Models\Approval\Paymentrequest', 'id', 'paymentrequest_id');
    }

    public function paymentrequestretractapprovals() {
        return $this->hasMany('\App\Models\Approval\Paymentrequestretractapproval', 'paymentrequestretract_id', 'id');
    }

    public function nextapprover() {
        $user = null;
        $approversetting = Approversetting::find($this::getAttribute('approversetting_id'));
        if ($approversetting)
        {
            if ($approversetting->approver_id > 0)
            {
                Log::info($approversetting->approver_id);
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
