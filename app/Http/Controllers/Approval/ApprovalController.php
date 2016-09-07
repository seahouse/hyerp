<?php

namespace App\Http\Controllers\Approval;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Approval\Reimbursement;
use App\Models\Approval\Paymentrequest;
use App\Models\Approval\Paymentrequestapproval;
use Auth, DB;
use Illuminate\Support\Facades\Input;

class ApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function mindexmy()
    {
        //
        $userid = Auth::user()->id;

        $page = Input::get('page', 1);
        $paginate = 10;

        // $reimbursements = Reimbursement::latest('created_at')
        //     ->select('id')
        //     ->where('applicant_id', $userid)->paginate(10);
        // $paymentrequests = Paymentrequest::latest('created_at')
        //     ->select('id')
        //     ->where('applicant_id', $userid)
        //     ->union($reimbursements)
        //     ->get();

        $reimbursements = DB::table('reimbursements')
            ->leftJoin('users', 'users.id', '=', 'reimbursements.applicant_id')
            ->select('reimbursements.id', 'users.name as applicant_name', 'reimbursements.approversetting_id as status', DB::raw('\'报销\' as type'), 'reimbursements.created_at', DB::raw('\'/approval/reimbursements/mshow/\' as url'))
            ->where('applicant_id', $userid);
        $paymentrequests = DB::table('paymentrequests')
            ->leftJoin('users', 'users.id', '=', 'paymentrequests.applicant_id')
            ->select('paymentrequests.id', 'users.name as applicant_name', 'paymentrequests.status', DB::raw('\'付款\' as type'), 'paymentrequests.created_at', DB::raw('\'/approval/paymentrequests/mshow/\' as url'))
            ->union($reimbursements)
            ->latest('created_at')
            // ->take(10)
            ->get();

        $offSet = ($page * $paginate) - $paginate;
        $itemsForCurrentPage = array_slice($paymentrequests, $offSet, $paginate, true);
        $items = new \Illuminate\Pagination\LengthAwarePaginator($itemsForCurrentPage, count($paymentrequests), $paginate, $page);

        // $approvals = $reimbursements + $paymentrequests;
        // dd($paymentrequests);

        return view('approval.mindexmy', compact('items'));
    }

    /**
     * 待我审批的.
     * 
     *
     * @return \Illuminate\Http\Response
     */
    public function mindexmyapproval()
    {
        $reimbursements = ReimbursementsController::myapproval();
        $paymentrequests = PaymentrequestsController::myapproval();

        // dd($reimbursements->toJson());
        // dd($reimbursements->toArray()["data"]);
        // dd($reimbursements);
        // $items = [];
        // foreach ($reimbursements as $value) {
        //     # code...
        //     dd(array_only($value, ['id', 'applicant_id', 'approversetting_id']));
        // }


        return view('approval.mindexmyapproval', compact('reimbursements', 'paymentrequests'));
    }

    /**
     * 我已审批的.
     *
     * @return \Illuminate\Http\Response
     */
    public function mindexmyapprovaled()
    {
        // 获取当前操作人员的报销审批层次
        $userid = Auth::user()->id;        
        $ids = [];      // 报销id数组
        $ids_paymentrequest = [];      // 报销id数组

         // 获取需要我审批的报销id数组
        $reimbursementids = Reimbursement::leftJoin('reimbursementapprovals', 'reimbursements.id', '=', 'reimbursementapprovals.reimbursement_id')
            ->select('reimbursements.id', 
                DB::raw('(select count(approver_id) from reimbursementapprovals where reimbursements.id=reimbursementapprovals.reimbursement_id and reimbursementapprovals.approver_id=' . $userid . ' limit 1) as myapprovaled'))     // 最后一次审批的状态
            ->get();

        foreach ($reimbursementids as $reimbursementid) {
            if ($reimbursementid->myapprovaled > 0)
                $ids = array_prepend($ids, $reimbursementid->id);
        }

        $reimbursements = Reimbursement::latest('created_at')->whereIn('id', $ids)->paginate(10);
        // $reimbursements = Reimbursement::whereIn('id', $ids)->paginate(10);
        
        $ids_paymentrequest = Paymentrequestapproval::where('approver_id', $userid)->select('paymentrequest_id')->distinct()->pluck('paymentrequest_id');
        $paymentrequests = Paymentrequest::latest('created_at')->whereIn('id', $ids_paymentrequest)->paginate(10);
        // dd($paymentrequests);

        return view('approval.mindexmyapprovaled', compact('reimbursements', 'paymentrequests'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
