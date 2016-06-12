<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;

use App\Models\System\User;

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

    public function approver2() {
        // $userid = Auth::user()->id;
        $approversetting = Approversetting::find($this::getAttribute('id'));
        if ($approversetting)
        {
            if ($approversetting->dept_id > 0 && strlen($approversetting->position) > 0)    // 设置了部门与职位才进行查找
            {
                $user = User::where('dept_id', $approversetting->dept_id)->where('position', $approversetting->position)->first();
                return $user->name; 
            }
            elseif ($approversetting->level == 2)       // 第二层没有设置部门与职位，则找出部门经理的人来匹配当前用户
            {
                // 按照"部门经理"来查找用户组
                $userids = User::where('position', 'like', '%'.$approversetting->position.'%')->pluck('id');
                if (in_array($userid, $userids->toArray()))
                {
                    $approversetting_id_my = $approversetting->id;
                    $approversetting_level = $approversetting->level;
                    break;
                }
            }
            elseif ($approversetting->level == 3) {     // 第三层没有设置部门与职位，则根据实际情况来确定哪个副总
                // 按照"副总经理"来查找用户组
                $userids = User::where('position', 'like', '%'.$approversetting->position.'%')->pluck('id');
                if (in_array($userid, $userids->toArray()))
                {
                    $approversetting_id_my = $approversetting->id;
                    $approversetting_level = $approversetting->level;
                    break;                    
                }
            }
        }
        return $this->hasMany('\App\Models\Approval\Reimbursementapprovals', 'reimbursement_id', 'id');
    }
}
