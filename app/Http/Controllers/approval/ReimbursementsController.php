<?php

namespace App\Http\Controllers\approval;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Approval\Reimbursement;
use App\Http\Controllers\DingTalkController;

class ReimbursementsController extends Controller
{
    //
	public function index()
	{

	}

	public function mindex()
	{
		return view('approval/reimbursements/mindex');
	}

	public function mcreate()
	{
		return view('approval/reimbursements/mcreate');
	}

    public function store(Request $request)
    {
    	$dingtalk = new DingTalkController();
    	$input = $request->all();
    	$reimbursement = Reimbursement::create($input);
    	dd($reimbursement);
    }
}
