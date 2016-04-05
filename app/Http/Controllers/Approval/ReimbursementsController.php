<?php

namespace App\Http\Controllers\approval;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Approval\Reimbursement;
use App\Http\Controllers\DingTalkController;
use App\Models\Approval\Approversetting;
use App\Models\Approval\Reimbursementimages;
use Auth, DB, Storage;

class ReimbursementsController extends Controller
{
    public static $approvaltype_id = 1;      // 报销类型的id
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
        $myleveltable = Approversetting::where('approvaltype_id', $this::$approvaltype_id)->where('approver_id', $userid)->first();
        $ids = [];      // 需要我审批的报销id数组
        if ($myleveltable)
        {
            $mylevel = $myleveltable->level;

            // 获取需要我审批的报销id数组
            $reimbursementids = Reimbursement::leftJoin('reimbursementapprovals', 'reimbursements.id', '=', 'reimbursementapprovals.reimbursement_id')
                ->select('reimbursements.id', DB::raw('coalesce(max(reimbursementapprovals.level), 0) as currentlevel'),
                    DB::raw('coalesce((select level from approversettings where level>coalesce(max(reimbursementapprovals.level), 0) order by level limit 1), 0) as nextlevel'),
                    DB::raw('coalesce((select status from reimbursementapprovals where reimbursements.id=reimbursementapprovals.reimbursement_id order by id desc limit 1), 0) as status'))     // 最后一次审批的状态
                ->groupBy('reimbursements.id')
                ->havingRaw('coalesce((select level from approversettings where level>coalesce(max(reimbursementapprovals.level), 0) order by level limit 1), 0) = ' . $mylevel)
                ->havingRaw('coalesce((select status from reimbursementapprovals where reimbursements.id=reimbursementapprovals.reimbursement_id order by id desc limit 1), 0)>=0')     // 审批通过的情况
                ->get();

            foreach ($reimbursementids as $reimbursementid) {
                $ids = array_prepend($ids, $reimbursementid->id);
            }
        }

        $reimbursements = Reimbursement::latest('created_at')->whereIn('id', $ids)->paginate(10);

        return view('approval.reimbursements.mindexmyapproval', compact('reimbursements'));
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
        $ids = [];      // 需要我审批的报销id数组

         // 获取需要我审批的报销id数组
        $reimbursementids = Reimbursement::leftJoin('reimbursementapprovals', 'reimbursements.id', '=', 'reimbursementapprovals.reimbursement_id')
            ->select('reimbursements.id', 
                DB::raw('(select count(approver_id) from reimbursementapprovals where reimbursements.id=reimbursementapprovals.reimbursement_id and reimbursementapprovals.approver_id=' . $userid . ' limit 1) as myapprovaled'))     // 最后一次审批的状态
            // ->groupBy('reimbursements.id')
            // ->havingRaw('coalesce((select level from approversettings where level>coalesce(max(reimbursementapprovals.level), 0) order by level limit 1), 0) = ' . $mylevel)
            // ->havingRaw('coalesce((select status from reimbursementapprovals where reimbursements.id=reimbursementapprovals.reimbursement_id order by id desc limit 1), 0)>=0')     // 审批通过的情况
            ->get();

        foreach ($reimbursementids as $reimbursementid) {
            if ($reimbursementid->myapprovaled > 0)
                $ids = array_prepend($ids, $reimbursementid->id);
        }

        $reimbursements = Reimbursement::latest('created_at')->whereIn('id', $ids)->paginate(10);

        return view('approval.reimbursements.mindexmyapprovaled', compact('reimbursements'));
    }

	public function mcreate()
	{
        // $dingtalk = new DingTalkController();
        // $config = $dingtalk->getconfig();
        $config = DingTalkController::getconfig();
		return view('approval/reimbursements/mcreate', compact('config'));
	}

    public function store(Request $request)
    {
    	$input = $request->all();

        $input['applicant_id'] = Auth::user()->id;
        $reimbursement = Reimbursement::create($input);
        return redirect('approval/reimbursements/mindex');
    }

    public function mstore(Request $request)
    {
        $input = $request->all();
        $images = array_where($input, function($key, $value) {
            if (substr_compare($key, 'image_', 0, 6) == 0)
                return $value;
        });

        $input['applicant_id'] = Auth::user()->id;
        $reimbursement = Reimbursement::create($input);

        // create reimbursement images
        if ($reimbursement)
        {
            foreach ($images as $key => $value) {
                # code...
                // save image file.
                $sExtension = substr($value, strrpos($value, '.') + 1);
                $sFilename = 'approval/reimbursement/' . $reimbursement->id .'/' . date('YmdHis').rand(100, 200) . '.' . $sExtension;
                Storage::disk('local')->put($sFilename, file_get_contents($value));

                // add image record
                $reimbursementimages = new Reimbursementimages;
                $reimbursementimages->reimbursement_id = $reimbursement->id;
                $reimbursementimages->path = $sFilename;
                $reimbursementimages->save();
            }
        }

        return redirect('approval/reimbursements/mindexmy');
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
