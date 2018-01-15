<?php

namespace App\Models\Approval;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Issuedrawing extends Model
{
    //
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'designdepartment',
        'sohead_id',
        'overview',
        'tonnage',
        'productioncompany',
        'materialsupplier',
        'drawingchecker_id',
        'requestdeliverydate',
        'drawingcount',
        'remark',
        'applicant_id',
        'status',
        'approversetting_id',
    ];

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
