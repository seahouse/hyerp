<?php

namespace App\Http\Controllers\approval;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Approval\Reimbursement;
use App\Http\Controllers\DingTalkController;
use Auth;

class ReimbursementsController extends Controller
{
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
}
