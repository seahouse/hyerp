<?php

namespace App\Models\Approval;

use App\Models\System\Dtuser;
use App\Models\System\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Issuedrawing extends Model
{
    //
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    private static $approvaltype_name = "下发图纸";

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
                else        // 其他特殊处理
                {
                    if ($approversetting->level == 1)
                    {
//                        $applicant = User::where('id', $this::getAttribute('applicant_id'))->first();
                        $applicant_dtuser = Dtuser::where('user_id', $this::getAttribute('applicant_id'))->first();
                        if (isset($applicant_dtuser))
                        {
                            $departmentList = json_decode($applicant_dtuser->department);
                            $department_id = 0;
                            if (count($departmentList) > 0)
                                $department_id = array_first($departmentList);
                            if ($department_id > 0)
                            {
                                $dtuserid = config('custom.dingtalk.approversettings.issuedrawing.level1.' . $department_id, '');
                                if (strlen($dtuserid) > 0)
                                {
                                    $dtuser = Dtuser::where('userid', $dtuserid)->firstOrFail();
                                    $user = User::findOrFail($dtuser->user_id);
                                }
                            }
                        }
                    }
                }
            }

        }

        return $user;
    }

    public function approvers() {
        $approverArray = [];
        $approvaltype = Approvaltype::where('name', self::$approvaltype_name)->firstOrFail();
        $approversettings = Approversetting::where('approvaltype_id', $approvaltype->id)->orderBy('level')->get();
        foreach ($approversettings as $approversetting)
        {
            $user = null;
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
                else        // 其他特殊处理
                {
                    if ($approversetting->level == 1)
                    {
//                        $applicant = User::where('id', $this::getAttribute('applicant_id'))->first();
                        $applicant_dtuser = Dtuser::where('user_id', $this::getAttribute('applicant_id'))->first();
                        if (isset($applicant_dtuser))
                        {
                            $departmentList = json_decode($applicant_dtuser->department);
                            $department_id = 0;
                            if (count($departmentList) > 0)
                                $department_id = array_first($departmentList);
                            if ($department_id > 0)
                            {
                                $dtuserid = config('custom.dingtalk.approversettings.issuedrawing.level1.' . $department_id, '');
                                if (strlen($dtuserid) > 0)
                                {
                                    $dtuser = Dtuser::where('userid', $dtuserid)->firstOrFail();
                                    $user = User::findOrFail($dtuser->user_id);
                                }
                            }
                        }
                    }
                    elseif ($approversetting->level == 2)
                    {
                        $applicant_dtuser = Dtuser::where('user_id', $this::getAttribute('applicant_id'))->first();
                        if (isset($applicant_dtuser))
                        {
                            $departmentList = json_decode($applicant_dtuser->department);
                            $department_id = 0;
                            if (count($departmentList) > 0)
                                $department_id = array_first($departmentList);
                            if ($department_id > 0)
                            {
                                $dtuserid = config('custom.dingtalk.approversettings.issuedrawing.level2.' . $department_id, '');
                                if (strlen($dtuserid) > 0)
                                {
                                    $dtuser = Dtuser::where('userid', $dtuserid)->firstOrFail();
                                    $user = User::findOrFail($dtuser->user_id);
                                }
                            }
                        }
                    }
                    elseif ($approversetting->level == 4)
                    {
                        $productioncompany = $this::getAttribute('productioncompany');
                        $dtuserid = config('custom.dingtalk.approversettings.issuedrawing.level4.' . $productioncompany, '');
                        if (strlen($dtuserid) > 0)
                        {
                            $dtuser = Dtuser::where('userid', $dtuserid)->firstOrFail();
                            $user = User::findOrFail($dtuser->user_id);
                        }
                    }
                    elseif ($approversetting->level == 5)
                    {
                        $applicant_dtuser = Dtuser::where('user_id', $this::getAttribute('applicant_id'))->first();
                        if (isset($applicant_dtuser))
                        {
                            $departmentList = json_decode($applicant_dtuser->department);
                            $department_id = 0;
                            if (count($departmentList) > 0)
                                $department_id = array_first($departmentList);
                            if ($department_id > 0)
                            {
                                $dtuserid = config('custom.dingtalk.approversettings.issuedrawing.level5.' . $department_id, '');
                                if (strlen($dtuserid) > 0)
                                {
                                    $dtuser = Dtuser::where('userid', $dtuserid)->firstOrFail();
                                    $user = User::findOrFail($dtuser->user_id);
                                }
                            }
                        }
                    }
                }
            }
            if (isset($user))
                array_push($approverArray, $user->dtuserid);
        }
//        dd($approverArray);
        $approvers = implode(',', $approverArray);
        return $approvers;
    }
}
