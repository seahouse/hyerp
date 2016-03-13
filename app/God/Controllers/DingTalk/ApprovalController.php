<?php

namespace App\God\Controllers\DingTalk;

use Illuminate\Http\Request;
use App\Http\Requests;
use DB, Auth;

class ApprovalController extends \App\God\Controllers\GodController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function home()
    {
        $viewTitle = trans('dingtalk.approval.title');
        return response()->view(parent::VIEW_NAMESPACE.'::'.'dingtalk.home', compact('viewTitle'));
    }

    public function requestToMe()
    {
        $viewTitle = trans('dingtalk.approval.requestToMe');
        $me = Auth::user()->id;
        $records = DB::table('reimbursements')
                  ->where(function ($query) use($me) {
                      $query->where('status', '=', 0)
                            ->where('approvaler1_id', '=', $me);
                  })
                  ->orWhere(function ($query) {
                      $query->where('status', '=', 1)
                            ->where('approvaler2_id', '=', Auth::user()->id);
                  })
                  ->orWhere(function ($query) {
                      $query->where('status', '=', 2)
                            ->where('approvaler3_id', '=', Auth::user()->id);
                  })
                  ->get();
        $models = [];
        foreach ($records as $record) {
            $state = 'unknown';
            $date = date('Y-m-d');
            switch ($record->status) {
            case 0:
                $state = trans('approval.reimbursement.status_initial');
                $date = $record->date;
                break;
            case 1:
                $state = trans('approval.reimbursement.status_1st_pass');
                $date = $record->approvaldate1;
                break;
            case 2:
                $state = trans('approval.reimbursement.status_2st_pass');
                $date = $record->approvaldate2;
                break;
            case 10:
                $state = trans('approval.reimbursement.status_3st_pass');
                $date = $record->approvaldate3;
                break;
            case -1:
                $state = trans('approval.reimbursement.status_1st_fail');
                $date = $record->approvaldate1;
                break;
            case -2:
                $state = trans('approval.reimbursement.status_2st_fail');
                $date = $record->approvaldate2;
                break;
            case -10:
                $state = trans('approval.reimbursement.status_3st_fail');
                $date = $record->approvaldate3;
                break;
            }
            $models[] = [
                'id' => $record->id,
                'approvaltype' => trans('dingtalk.approval.reimburse'),
                'applicant' => DB::table('users')->where('id', '=', $record->applicant_id)->value('name'),
                'status' => $state,
                'date' => $date,
            ];
        }
        return response()->view(parent::VIEW_NAMESPACE.'::'.'dingtalk.list', compact('viewTitle', 'models'));
    }

    public function handledByMe()
    {
        $viewTitle = trans('dingtalk.approval.handledByMe');
        $me = Auth::user()->id;
        $records = DB::table('reimbursements')
                  ->where(function ($query) use($me) {
                      $query->where('status', '=', 1)
                            ->where('approvaler1_id', '=', $me);
                  })
                  ->orWhere(function ($query) {
                      $query->where('status', '=', 2)
                            ->where('approvaler1_id', '=', Auth::user()->id);
                  })
                  ->orWhere(function ($query) {
                      $query->where('status', '=', 2)
                            ->where('approvaler2_id', '=', Auth::user()->id);
                  })
                  ->orWhere(function ($query) {
                      $query->where('status', '=', 10)
                            ->where('approvaler1_id', '=', Auth::user()->id);
                  })
                  ->orWhere(function ($query) {
                      $query->where('status', '=', 10)
                            ->where('approvaler2_id', '=', Auth::user()->id);
                  })
                  ->orWhere(function ($query) {
                      $query->where('status', '=', 10)
                            ->where('approvaler3_id', '=', Auth::user()->id);
                  })
                  ->orWhere(function ($query) use($me) {
                      $query->where('status', '=', -1)
                            ->where('approvaler1_id', '=', $me);
                  })
                  ->orWhere(function ($query) {
                      $query->where('status', '=', -2)
                            ->where('approvaler1_id', '=', Auth::user()->id);
                  })
                  ->orWhere(function ($query) {
                      $query->where('status', '=', -2)
                            ->where('approvaler2_id', '=', Auth::user()->id);
                  })
                  ->orWhere(function ($query) {
                      $query->where('status', '=', -10)
                            ->where('approvaler1_id', '=', Auth::user()->id);
                  })
                  ->orWhere(function ($query) {
                      $query->where('status', '=', -10)
                            ->where('approvaler2_id', '=', Auth::user()->id);
                  })
                  ->orWhere(function ($query) {
                      $query->where('status', '=', -10)
                            ->where('approvaler3_id', '=', Auth::user()->id);
                  })
                  ->get();
        $models = [];
        foreach ($records as $record) {
            $state = 'unknown';
            $date = date('Y-m-d');
            switch ($record->status) {
            case 0:
                $state = trans('approval.reimbursement.status_initial');
                $date = $record->date;
                break;
            case 1:
                $state = trans('approval.reimbursement.status_1st_pass');
                $date = $record->approvaldate1;
                break;
            case 2:
                $state = trans('approval.reimbursement.status_2st_pass');
                $date = $record->approvaldate2;
                break;
            case 10:
                $state = trans('approval.reimbursement.status_3st_pass');
                $date = $record->approvaldate3;
                break;
            case -1:
                $state = trans('approval.reimbursement.status_1st_fail');
                $date = $record->approvaldate1;
                break;
            case -2:
                $state = trans('approval.reimbursement.status_2st_fail');
                $date = $record->approvaldate2;
                break;
            case -10:
                $state = trans('approval.reimbursement.status_3st_fail');
                $date = $record->approvaldate3;
                break;
            }
            $models[] = [
                'id' => $record->id,
                'approvaltype' => trans('dingtalk.approval.reimburse'),
                'applicant' => DB::table('users')->where('id', '=', $record->applicant_id)->value('name'),
                'status' => $state,
                'date' => $date,
            ];
        }
        return response()->view(parent::VIEW_NAMESPACE.'::'.'dingtalk.list', compact('viewTitle', 'models'));
    }

    public function requestByMe()
    {
        $viewTitle = trans('dingtalk.approval.requestByMe');
        $me = DB::table('users')->where('id', '=', Auth::user()->id)->value('name');
        $me = Auth::user()->id;
        $status = [
            '0' => trans('approval.reimbursement.status_initial'),
            '1' => trans('approval.reimbursement.status_1st_pass'),
            '2' => trans('approval.reimbursement.status_2nd_pass'),
            '10' => trans('approval.reimbursement.status_3rd_pass'),
            '-1' => trans('approval.reimbursement.status_1st_fail'),
            '-2' => trans('approval.reimbursement.status_2st_fail'),
            '-10'=> trans('approval.reimbursement.status_3st_fail'),
        ];
        $records = DB::table('reimbursements')
                  ->where('applicant_id', '=', $me)
                  ->get();
        $models = [];
        foreach ($records as $record) {
            $date = date('Y-m-d');
            $models[] = [
                'id' => $record->id,
                'approvaltype' => trans('dingtalk.approval.reimburse'),
                'applicant' => '我',
                'status' => $status[$record->status],
                'date' => $record->date,
            ];
        }
        return response()->view(parent::VIEW_NAMESPACE.'::'.'dingtalk.list', compact('viewTitle', 'models'));
    }
}
