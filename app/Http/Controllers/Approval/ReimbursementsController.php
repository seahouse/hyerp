<?php

namespace App\Http\Controllers\approval;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Approval\Reimbursement;
use App\Http\Controllers\DingTalkController;
use App\Models\Approval\Approversetting;
use App\Models\Approval\Reimbursementimages;
use App\Models\Approval\Reimbursementtravel;
use App\Models\System\User;
use App\Models\System\Dept;
use Auth, DB, Storage;
use Log;

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
     * 员工 财务出纳 部门主管  分管副总 总经理 财务主办
     *
     * @return \Illuminate\Http\Response
     */
    public function mindexmyapproval()
    {
        // 登录人在审批流程中的位置
        $userid = Auth::user()->id;
        $approversettings = Approversetting::where('approvaltype_id', $this::$approvaltype_id)->orderBy('level')->get();
        $approversetting_id_my = 0;
        $approversetting_level = 0;
        foreach ($approversettings as $approversetting) {
            if ($approversetting->dept_id > 0 && strlen($approversetting->position) > 0)    // 设置了部门与职位才进行查找
            {
                $user = User::where('dept_id', $approversetting->dept_id)->where('position', $approversetting->position)->first();
                if ($user->id == $userid)
                {
                    $approversetting_id_my = $approversetting->id;
                    $approversetting_level = $approversetting->level;
                    break;
                }            
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
            

            // 如果是
        }
        
        // 如果当前操作人员在审批流程中
        // 先随意查询一个结果给$reimbursements赋值
        $reimbursements = Reimbursement::where('id', -1)->paginate(10);
        if ($approversetting_id_my > 0)
        {           

            if ($approversetting_level == 2)
            {
                $deptid = Auth::user()->dept_id;
                if ($deptid > 0)
                {
                    $reimbursements = Reimbursement::leftJoin('users', 'reimbursements.applicant_id', '=', 'users.id')
                        ->select('reimbursements.*')
                        ->where('reimbursements.approversetting_id', $approversetting_id_my)
                        ->where('users.dept_id', $deptid)->paginate(10);
                }
            }
            elseif ($approversetting_level == 3) {
                $dtuserid = Auth::user()->dtuserid;
                $deptnames = config('custom.dingtalk.approversettings.reimbursement.level3.'.$dtuserid);
                $deptids = [];
                foreach ($deptnames as $deptname) {
                    $dept = Dept::where('name', $deptname)->first();
                    if ($dept)
                        array_push($deptids, $dept->id);
                }
                
                $reimbursements = Reimbursement::leftJoin('users', 'reimbursements.applicant_id', '=', 'users.id')
                        ->select('reimbursements.*')
                        ->where('reimbursements.approversetting_id', $approversetting_id_my)
                        ->whereIn('users.dept_id', $deptids)->paginate(10);
            }
            else
                $reimbursements = Reimbursement::latest('created_at')->where('approversetting_id', $approversetting_id_my)->paginate(10);
        }


        
        // // 获取当前操作人员的报销审批层次
        // $userid = Auth::user()->id;
        // $myleveltable = Approversetting::where('approvaltype_id', $this::$approvaltype_id)->where('approver_id', $userid)->first();
        // $ids = [];      // 需要我审批的报销id数组
        // if ($myleveltable)
        // {
        //     $mylevel = $myleveltable->level;

        //     // 获取需要我审批的报销id数组
        //     $reimbursementids = Reimbursement::leftJoin('reimbursementapprovals', 'reimbursements.id', '=', 'reimbursementapprovals.reimbursement_id')
        //         ->select('reimbursements.id', DB::raw('coalesce(max(reimbursementapprovals.level), 0) as currentlevel'),
        //             DB::raw('coalesce((select level from approversettings where level>coalesce(max(reimbursementapprovals.level), 0) order by level limit 1), 0) as nextlevel'),
        //             DB::raw('coalesce((select status from reimbursementapprovals where reimbursements.id=reimbursementapprovals.reimbursement_id order by id desc limit 1), 0) as status'))     // 最后一次审批的状态
        //         ->groupBy('reimbursements.id')
        //         ->havingRaw('coalesce((select level from approversettings where level>coalesce(max(reimbursementapprovals.level), 0) order by level limit 1), 0) = ' . $mylevel)
        //         ->havingRaw('coalesce((select status from reimbursementapprovals where reimbursements.id=reimbursementapprovals.reimbursement_id order by id desc limit 1), 0)>=0')     // 审批通过的情况
        //         ->get();

        //     foreach ($reimbursementids as $reimbursementid) {
        //         $ids = array_prepend($ids, $reimbursementid->id);
        //     }
        // }
        // $reimbursements = Reimbursement::latest('created_at')->whereIn('id', $ids)->paginate(10);

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

    public function check(Request $request)
    {
        $input = $request->all();
        $data = [];

        // 天数
        $travels = array_where($input, function($key, $value) {     
            if (substr_compare($key, 'travel_', 0, 7) == 0)
                return $value;
        });
        $travelList = [];
        foreach ($travels as $key => $value) {
            $hh = substr($key, 0, 9);
            $kk = substr($key, 9);
            if (!array_has($travelList, $hh))
                $travelList[$hh] = array($kk => $value);
            else
                $travelList[$hh] = array_add($travelList[$hh], $kk, $value);
        }
        $daysTotal = 0;
        foreach ($travelList as $key => $value) {
            $d1 = date_create($value['datego']);
            $d2 = date_create($value['dateback']);
            $interval = date_diff($d1, $d2);
            $daysTotal += $interval->days + 1;
        }

        $data['days'] = $daysTotal;
        $data['mealamount'] = $daysTotal * 50;

        // 交通费合计
        $data['ticketamount'] = $request->input('amountAirfares', 0.0) + $request->input('amountTrain', 0.0) + $request->input('amountTaxi', 0.0) + $request->input('amountOtherTicket', 0.0);

        // 总计
        $data['amountTotal'] = $data['mealamount'] + $data['ticketamount'] + $request->input('stayamount', 0.0) + $request->input('otheramount', 0.0);

        // 平均统计
        $data['stayamountPer'] = $request->input('stayamount', 0.0) / $daysTotal;
        $data['amountPer'] = $data['amountTotal'] / $daysTotal; 
        $data['status'] = 'OK';

        return json_encode($data);
        // return $data;
    }

    public function mstore(Request $request)
    {

        $input = $request->all();
        // dd($input);

        // generation number
        $cPre = $input['numberpre'];
        $lastReimbursement = Reimbursement::where('number', 'like', $cPre.date('Ymd').'%')->orderBy('id', 'desc')->first();
        if ($lastReimbursement)
        {
            $lastNumber = $lastReimbursement->number;
            $suffix = (string)((int)substr($lastNumber, -2) + 1);
            $suffix = str_pad($suffix, 2, '0', STR_PAD_LEFT);
            // dd($suffix);
            $number = substr($lastNumber, 0, strlen($lastNumber) - 2) . $suffix;
        }
        else
            $number = $cPre . date('Ymd') . '01';
        $input['number'] = $number;        

        $input['applicant_id'] = Auth::user()->id;

        // set approversetting_id 
        $approversettingFirst = Approversetting::where('approvaltype_id', $this::$approvaltype_id)->orderBy('level')->first();
        if ($approversettingFirst)
            $input['approversetting_id'] = $approversettingFirst->id;
        else
            $input['approversetting_id'] = -1;

        $reimbursement = Reimbursement::create($input);

        // create reimbursement travels
        if ($reimbursement)
        {
            $travels = array_where($input, function($key, $value) {     
                if (substr_compare($key, 'travel_', 0, 7) == 0)
                    return $value;
            });
            $travelList = [];
            foreach ($travels as $key => $value) {
                $hh = substr($key, 0, 9);
                $kk = substr($key, 9);
                if (!array_has($travelList, $hh))
                    $travelList[$hh] = array($kk => $value);
                else
                    $travelList[$hh] = array_add($travelList[$hh], $kk, $value);

            }

            $seq = 0;
            foreach ($travelList as $key => $value) {
                // add reimbursementtravels record
                $value['reimbursement_id'] = $reimbursement->id;
                $value['seq'] = ++$seq;
                Reimbursementtravel::create($value);
            }
        }

        // create reimbursement images
        if ($reimbursement)
        {
            $images = array_where($input, function($key, $value) {
                if (substr_compare($key, 'image_', 0, 6) == 0)
                    return $value;
            });

            foreach ($images as $key => $value) {
                # code...
                // save image file.
                $sExtension = substr($value, strrpos($value, '.') + 1);
                // $sFilename = 'approval/reimbursement/' . $reimbursement->id .'/' . date('YmdHis').rand(100, 200) . '.' . $sExtension;
                // Storage::disk('local')->put($sFilename, file_get_contents($value));
                // Storage::move($sFilename, '../abcd.jpg');
                $dir = 'images/approval/reimbursement/' . $reimbursement->id . '/' . date('YmdHis').rand(100, 200) . '.' . $sExtension;
                $parts = explode('/', $dir);
                $filename = array_pop($parts);
                $dir = '';
                foreach ($parts as $part) {
                    # code...
                    $dir .= "$part/";
                    if (!is_dir($dir)) {
                        mkdir($dir);
                    }
                }                

                file_put_contents("$dir/$filename", file_get_contents($value));
                // file_put_contents('abcd.jpg', file_get_contents($value));

                // response()->download($value);
                // Storage::put('abcde.jpg', file_get_contents($value));
                // copy(storage_path('app') . '/' . $sFilename, '/images/' . $sFilename);

                // add image record
                $reimbursementimages = new Reimbursementimages;
                $reimbursementimages->reimbursement_id = $reimbursement->id;
                $reimbursementimages->path = "/$dir/$filename";     // add a '/' in the head.
                $reimbursementimages->save();
            }
        }

        return redirect('approval/reimbursements/mindexmy');
    }

    public function search($key)
    {
        $reimbursements = Reimbursement::latest('created_at')->where('number', $key)->paginate(10);
        return $reimbursements;
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
