<?php

namespace App\Http\Controllers\approval;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Approval\Reimbursement;
use App\Http\Controllers\DingTalkController;
use App\Models\Approval\Approversetting;
use Auth, DB;

class ReimbursementsController extends Controller
{
    protected $approvaltype_id = 1;      // 报销类型的id
    //
	public function index()
	{
        $reimbursements = Reimbursement::latest('created_at')->paginate(10);
        return view('approval.reimbursements.index', compact('reimbursements'));
	}

	public function mindex()
	{
		$reimbursements = Reimbursement::latest('created_at')->paginate(10);
        return view('approval.reimbursements.mindex', compact('reimbursements'));
	}

	// 我发起的
	public function mindexmy()
	{
		$userid = Auth::user()->id;
		$reimbursements = Reimbursement::latest('created_at')->where('applicant_id', $userid)->paginate(10);

        return view('approval.reimbursements.mindexmy', compact('reimbursements'));
	}

    /**
     * 待我审批的.
     *
     * @return \Illuminate\Http\Response
     */
    public function mindexmyapproval()
    {
        // 获取当前操作人员的报销审批层次
        $userid = Auth::user()->id;
        $myleveltable = Approversetting::where('approvaltype_id', $this->approvaltype_id)->where('approver_id', $userid)->first();
        $ids = [];      // 需要我审批的报销id数组
        if ($myleveltable)
        {
            $mylevel = $myleveltable->level;

            // 获取需要我审批的报销id数组
            $reimbursementids = Reimbursement::leftJoin('reimbursementapprovals', 'reimbursements.id', '=', 'reimbursementapprovals.reimbursement_id')
                ->select('reimbursements.id', DB::raw('coalesce(max(reimbursementapprovals.level), 0) as currentlevel'),
                    DB::raw('coalesce((select level from approversettings where level>coalesce(max(reimbursementapprovals.level), 0) order by level limit 1), 0) as nextlevel'))
                ->groupBy('reimbursements.id')
                ->havingRaw('coalesce((select level from approversettings where level>coalesce(max(reimbursementapprovals.level), 0) order by level limit 1), 0) = ' . $mylevel)
                ->get();

            foreach ($reimbursementids as $reimbursementid) {
                $ids = array_prepend($ids, $reimbursementid->id);
            }
        }

        $reimbursements = Reimbursement::latest('created_at')->whereIn('id', $ids)->paginate(10);

        return view('approval.reimbursements.mindexmyapproval', compact('reimbursements'));
        // return view('approval.reimbursementapprovals.create', compact('reimbursements'));
    }

	public function mcreate()
	{
		return view('approval/reimbursements/mcreate');
	}

    public function store(Request $request)
    {
    	$dingtalk = new DingTalkController();
    	$input = $request->all();

        $input['applicant_id'] = Auth::user()->id;
        $reimbursement = Reimbursement::create($input);
        return redirect('approval/reimbursements/mindex');
    }

    public function mstore(Request $request)
    {
        $dingtalk = new DingTalkController();
        $input = $request->all();

        $input['applicant_id'] = Auth::user()->id;
        $reimbursement = Reimbursement::create($input);
        return redirect('approval/reimbursements/mindexmy');

        // if (session()->has('userid'))
        // {
        //     $input['applicant_id'] = session()->get('userid');
        //     $reimbursement = Reimbursement::create($input);
        //     return redirect('approval/reimbursements/mindex');
        // }
        // else
        //     return '您的账号未与后台系统绑定，无法执行此操作.';
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function mshow($id)
    {
        //
        $reimbursement = Reimbursement::findOrFail($id);
        return view('approval.reimbursements.mshow', compact('reimbursement'));
    }
}
