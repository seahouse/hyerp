<?php

namespace App\Http\Controllers\System;

// use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\System\Dtuser2;
use App\Models\System\User;
use App\Http\Requests\System\UserRequest;
use Request;
use App\Http\Requests\System\UpdateUserRequest;
use App\Http\Requests\System\UpdateUserPassRequest;
use App\Models\System\Role;
use App\Models\System\Dtuser;
use Zizaco\Entrust\Entrust;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DingTalkController;
use App\Models\System\Userold;
use App\Models\System\Employee_hxold;
use Log, PinYin;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        $request = request();
        $key = $request->input('key', '');
        $inputs = $request->all();
        if (null !== request('key'))
            $users = $this->searchrequest($request);
        else
            $users = User::latest('created_at')->paginate(10);
//        $users = User::latest('created_at')->paginate(10);

        if (null !== request('key'))
            return view('system.users.index', compact('users', 'key', 'inputs'));
        else
            return view('system.users.index', compact('users'));
//        return view('system.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        if (Auth::user()->can('system_user_maintain'))
            return view('system.users.create');
        else 
            return '无此操作权限';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(UserRequest $request)
    {
        //
//         $input = Request::all();
//         Dept::create($input);
//         return redirect('system/depts');

        $data = [
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ];
//         return $data;
        
        User::create($data);
        return redirect('system/users');
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
        $user = User::findOrFail($id);
        return view('system.users.edit', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function editpass($id)
    {
        //
        $user = User::findOrFail($id);
        return view('system.users.editpass', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        //
        $user = User::findOrFail($id);
        $user->name = $request->input('name');
        // $user->password = bcrypt($request->input('password'));
		$user->dtuserid = $request->input('dtuserid');
        $user->dept_id = $request->input('dept_id');
        $user->position = $request->input('position');        
        $user->update();

        $dtuser = $user->dingtalkGetUser();
        if ($dtuser)
        {
            // dd($dtuser);
            // $dtuser2 = Dtuser::firstOrCreate(['userid' => $dtuser->userid])
            //     ->update($dtuser);
            $dtuser2 = Dtuser::firstOrNew(['userid' => $dtuser->userid]);
            // foreach ($dtuser as $key => $value) {
            //     $dtuser2->$key = $value;
            // }
            $dtuser2->user_id       = $user->id;

            $dtuser2->name          = $dtuser->name;
            if (isset($dtuser->tel))        $dtuser2->tel           = $dtuser->tel;
            if (isset($dtuser->workPlace))  $dtuser2->workPlace     = $dtuser->workPlace;
            if (isset($dtuser->remark))     $dtuser2->remark        = $dtuser->remark;
            $dtuser2->mobile        = $dtuser->mobile;
            if (isset($dtuser->email))      $dtuser2->email         = $dtuser->email;
            // $dtuser2->orgEmail      = $dtuser->orgEmail;             // 无此元素
            $dtuser2->active        = $dtuser->active;
            $dtuser2->orderInDepts  = $dtuser->orderInDepts;
            $dtuser2->isAdmin       = $dtuser->isAdmin;
            $dtuser2->isBoss        = $dtuser->isBoss;
            $dtuser2->dingId        = $dtuser->dingId;
            $dtuser2->isLeaderInDepts = $dtuser->isLeaderInDepts;
            $dtuser2->isHide        = $dtuser->isHide;
            // $dtuser2->department    = $dtuser->department;           // 是个数组，暂不考虑
            $dtuser2->position      = $dtuser->position;
            $dtuser2->avatar        = $dtuser->avatar;
            $dtuser2->jobnumber     = $dtuser->jobnumber;
            // $dtuser2->extattr       = $dtuser->extattr;              // 无此元素
            $dtuser2->save();
            // Dtuser::where('userid', $dtuser->userid)->update($dtuser);
            // $dtuser2->update($dtuser);
        }

        if ($user)
        {
            $sFilename = '';     
            if (Request::hasFile('avatar'))
            {
                dd($request->all());   
                $file = $request->file('avatar');
                $sFilename = $this->saveImg($file);             

            }           

            $user->avatar = $sFilename;
            $user->update();
        }


        return redirect('system/users');
    }

    public static function updatedtuser($dtuserid)
    {
        $dtuser2 = Dtuser::where('userid', $dtuserid)->firstOrFail();
        // $dtuser2 = Dtuser::firstOrFail(['userid' => $dtuser->userid]);
        // $dtuser2->user_id       = $user->id;
        $dtuser = DingTalkController::userGet($dtuserid);
        if ($dtuser)
        {
            $dtuser2->name          = $dtuser->name;
            if (isset($dtuser->tel))        $dtuser2->tel           = $dtuser->tel;
            if (isset($dtuser->workPlace))  $dtuser2->workPlace     = $dtuser->workPlace;
            if (isset($dtuser->remark))     $dtuser2->remark        = $dtuser->remark;
            $dtuser2->mobile        = $dtuser->mobile;
            if (isset($dtuser->email))      $dtuser2->email         = $dtuser->email;
            // $dtuser2->orgEmail      = $dtuser->orgEmail;             // 无此元素
            $dtuser2->active        = $dtuser->active;
            $dtuser2->orderInDepts  = $dtuser->orderInDepts;
            $dtuser2->isAdmin       = $dtuser->isAdmin;
            $dtuser2->isBoss        = $dtuser->isBoss;
            $dtuser2->dingId        = $dtuser->dingId;
            $dtuser2->isLeaderInDepts = $dtuser->isLeaderInDepts;
            $dtuser2->isHide        = $dtuser->isHide;
            // $dtuser2->department    = $dtuser->department;           // 是个数组，暂不考虑
            $dtuser2->position      = $dtuser->position;
            $dtuser2->avatar        = $dtuser->avatar;
            $dtuser2->jobnumber     = $dtuser->jobnumber;
            // $dtuser2->extattr       = $dtuser->extattr;              // 无此元素
            $dtuser2->save();
            // Dtuser::where('userid', $dtuser->userid)->update($dtuser);
            // $dtuser2->update($dtuser);
        }
    }

    // synchronize dingtalk user
    // if user is not exist, create.
    public static function synchronizedtuser($dtuser)
    {
        // 修改：根据名称来同步，不再根据邮箱，2020/1/20
        if (isset($dtuser->orgEmail) && !empty($dtuser->orgEmail))
            $user = User::where('email', $dtuser->orgEmail)->first();
        else
            $user = User::where('name', $dtuser->name)->first();

        if (!isset($user))
        {
            $user = new User;

            $pinyins = Pinyin::convert($dtuser->name);
            $email = implode("", $pinyins) . config('custom.dingtalk.email_suffix');
            Log::info($email);
            $user->email = $email;

            $user->password     = bcrypt('123456');
        }
        $user->name         = $dtuser->name;
//        $user->email        = $dtuser->orgEmail;
        $user->dtuserid        = $dtuser->userid;
        $user->save();

        $dtuserlocal = Dtuser::firstOrNew(['userid' => $dtuser->userid]);
        $dtuserlocal->user_id       = $user->id;
        $dtuserlocal->name          = $dtuser->name;
        $dtuserlocal->tel           = isset($dtuser->tel) ? $dtuser->tel : '';
        $dtuserlocal->workPlace    = isset($dtuser->workPlace) ? $dtuser->workPlace : '';
        $dtuserlocal->remark        = isset($dtuser->remark) ? $dtuser->remark : '';
        $dtuserlocal->mobile        = $dtuser->mobile;
        if (isset($dtuser->email))      $dtuserlocal->email         = $dtuser->email;
        $dtuserlocal->orgEmail      = isset($dtuser->orgEmail) ? $dtuser->orgEmail : '';
        $dtuserlocal->active        = $dtuser->active;
        $dtuserlocal->orderInDepts  = isset($dtuser->orderInDepts) ? $dtuser->orderInDepts : '';
        $dtuserlocal->isAdmin       = $dtuser->isAdmin;
        $dtuserlocal->isBoss        = $dtuser->isBoss;
        $dtuserlocal->dingId        = $dtuser->dingId;
        $dtuserlocal->isLeaderInDepts = isset($dtuser->isLeaderInDepts) ? $dtuser->isLeaderInDepts : '';
        $dtuserlocal->isHide        = $dtuser->isHide;
        $dtuserlocal->department    = json_encode($dtuser->department);           // 是个数组，暂不考虑
        $dtuserlocal->position      = $dtuser->position;
        $dtuserlocal->avatar        = $dtuser->avatar;
        $dtuserlocal->jobnumber     = $dtuser->jobnumber;
        $dtuserlocal->save();

        self::updateuseroldone($user);
    }

    // 河南华星同步用户信息。通过员工姓名进行对应，需要先在无锡华星中新建账号并设置企业邮箱
    public static function synchronizedtuser2($dtuser)
    {
        if (isset($dtuser->name) && !empty($dtuser->name))
        {
            $user = User::where('name', $dtuser->name)->first();
            if (isset($user))
            {
//                $user->name         = $dtuser->name;
//                $user->email        = $dtuser->orgEmail;
//                $user->dtuserid        = $dtuser->userid;
//                $user->save();

                $dtuserlocal = Dtuser2::firstOrNew(['userid' => $dtuser->userid]);
//            if (!isset($dtuserlocal))
//                $dtuserlocal = new Dtuser;
                $dtuserlocal->user_id       = $user->id;
                $dtuserlocal->name          = $dtuser->name;
                $dtuserlocal->tel           = isset($dtuser->tel) ? $dtuser->tel : '';
                $dtuserlocal->workPlace    = isset($dtuser->workPlace) ? $dtuser->workPlace : '';
                $dtuserlocal->remark        = isset($dtuser->remark) ? $dtuser->remark : '';
                $dtuserlocal->mobile        = $dtuser->mobile;
                if (isset($dtuser->email))      $dtuserlocal->email         = $dtuser->email;
                $dtuserlocal->orgEmail      = isset($dtuser->orgEmail) ? $dtuser->orgEmail : '';
                $dtuserlocal->active        = $dtuser->active;
                $dtuserlocal->orderInDepts  = isset($dtuser->orderInDepts) ? $dtuser->orderInDepts : '';
                $dtuserlocal->isAdmin       = $dtuser->isAdmin;
                $dtuserlocal->isBoss        = $dtuser->isBoss;
                $dtuserlocal->dingId        = isset($dtuser->dingId) ? $dtuser->dingId : '';
                $dtuserlocal->isLeaderInDepts = isset($dtuser->isLeaderInDepts) ? $dtuser->isLeaderInDepts : '';
                $dtuserlocal->isHide        = $dtuser->isHide;
                $dtuserlocal->department    = json_encode($dtuser->department);           // 是个数组，暂不考虑
                $dtuserlocal->position      = $dtuser->position;
                $dtuserlocal->avatar        = $dtuser->avatar;
                $dtuserlocal->jobnumber     = $dtuser->jobnumber;
                // $dtuserlocal->extattr       = $dtuser->extattr;              // 无此元素
                $dtuserlocal->save();
            }
        }
    }

    // delete dtuser
    // when dingtalk delete the user, do this
    public static function destroydtuser($dtuserid)
    {
        $dtuserlocal = Dtuser::where('userid', $dtuserid);
        if (isset($dtuserlocal))
        {
            $user = $dtuserlocal->first()->user;
            if (isset($user))
            {
                $user->dtuserid = "";
                $user->save();
            }
            $dtuserlocal->delete();
        }
    }

    // 河南华星
    public static function destroydtuser2($dtuserid)
    {
        $dtuserlocal = Dtuser2::where('userid', $dtuserid);
        if (isset($dtuserlocal))
        {
            $dtuserlocal->delete();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function updatepass(UpdateUserPassRequest $request, $id)
    {
        //
        $user = User::findOrFail($id);
        $user->password = bcrypt($request->input('password'));
        $user->update();
        return redirect('system/users');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        if (\Gate::denies('delete-user')) {
            return "You have no permission to do this!";
        }
        //
        User::destroy($id);
        return redirect('system/users');
    }
    
    public function editrole($id)
    {
        $user = User::findOrFail($id);
        return view('system.users.editrole', compact('user'));
    }
    
    public function updaterole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $role = Role::findOrFail($request->input('role_id'));
        if ($role <> null)
            if (!$user->hasRole($role->name))
                $user->attachRole($role);
        
        return redirect('system/users');
    }

    public function bingdingtalk()
    {
        $data = DingTalkController::register_call_back_user();

        if ($data->errcode == "0")
            dd($data->errmsg);
        else
            dd($data->errcode . ': ' . $data->errmsg);
    }

    public function bingdingtalkcancel()
    {
        $data = DingTalkController::register_call_back_user();

        if ($data->errcode == "0")
            dd($data->errmsg);
        else
            dd($data->errcode . ': ' . $data->errmsg);
    }

    public function binddingtalk2()
    {
        $data = DingTalkController::register_call_back_user2();

        if ($data->errcode == "0")
            return $data->errmsg;
        else
            return $data->errcode . ': ' . $data->errmsg;
    }

    public function binddingtalk2cancel()
    {
        $data = DingTalkController::register_call_back_user();

        if ($data->errcode == "0")
            dd($data->errmsg);
        else
            dd($data->errcode . ': ' . $data->errmsg);
    }

    private function saveImg($file)
    {
        $fileOriginalName = $file->getClientOriginalName();
        $sExtension = substr($fileOriginalName, strrpos($fileOriginalName, '.') + 1);
        $sFilename = date('YmdHis').rand(100, 200) . '.' . $sExtension;
        $file->move('images', $sFilename);
        return 'images/' . $sFilename;
    }

    public function edituserold($id)
    {
        //
        $user = User::findOrFail($id);
        return view('system.users.edituserold', compact('user'));
    }

    public function google2fa($id)
    {
        //
        $user = User::findOrFail($id);
        return view('system.users.editgoogle2fa', compact('user'));
    }

    public function updateuserold(Request $request, $id)
    {
        //
//        dd(Request::all());
        $user = User::findOrFail($id);
//        $userold = Userold::firstOrNew(['user_id' => $id]);
        $userold = Userold::where('user_id', $id)->first();
        if (isset($userold))
        {
            $userold->user_hxold_id = Request::input('user_hxold_id');
            $userold->save();
        }
        else
        {
            $userold = new Userold;
            $userold->user_id = $id;
            $userold->user_hxold_id = Request::input('user_hxold_id');
            $userold->save();
        }
//        dd($userold);
//        $userold->user_hxold_id = $request->input('user_hxold_id');
//        $userold->save();
//        $user->update();
        return redirect('system/users');
    }

    // update all userold
    // if not set already, add it and set it.
    public function updateuseroldall(Request $request)
    {
        //
//        dd(Request::all());
        $users = User::all();

        foreach ($users as $user)
        {
            $userold = Userold::where('user_id', $user->id)->first();
            if (isset($userold))
            {
//                Employee_hxold::where('name', '');
//                $userold->user_hxold_id = Request::input('user_hxold_id');
//                $userold->save();
            }
            else
            {
                $employeehxold = Employee_hxold::where('name', $user->name)->first();
                if (isset($employeehxold))
                {
//                    dd($employeehxold);

                    $userold = new Userold;
                    $userold->user_id = $user->id;
                    $userold->user_hxold_id = $employeehxold->id;
                    $userold->save();
                }

            }
        }
//        dd($users);

//        dd($userold);
//        $userold->user_hxold_id = $request->input('user_hxold_id');
//        $userold->save();
//        $user->update();
        return redirect('system/users');
    }

    public static function updateuseroldone($user)
    {
        $userold = Userold::where('user_id', $user->id)->first();
        if (isset($userold))
        {
//                Employee_hxold::where('name', '');
//                $userold->user_hxold_id = Request::input('user_hxold_id');
//                $userold->save();
        }
        else
        {
            $employeehxold = Employee_hxold::where('name', $user->name)->first();
            if (isset($employeehxold))
            {
//                    dd($employeehxold);

                $userold = new Userold;
                $userold->user_id = $user->id;
                $userold->user_hxold_id = $employeehxold->id;
                $userold->save();
            }
        }
    }

    public function updategoogle2fa(Request $request, $id)
    {
        //
//        dd(Request::all());
        $user = User::findOrFail($id);
        $user->google2fa_secret = Request::input('google2fa_secret');
        $user->save();

        return redirect('system/users');
    }

    public function search(Request $request)
    {
        $key = Request::input('key');
        $inputs = Request::all();
        $users = $this->searchrequest($request);
//        $purchaseorders = Purchaseorder_hxold::whereIn('id', $paymentrequests->pluck('pohead_id'))->get();
//        $totalamount = Paymentrequest::sum('amount');

        return view('system.users.index', compact('users', 'key', 'inputs'));
    }

    public function searchrequest($request)
    {
        $inputs = Request::all();
        $key = Request::input('key');

//        if (strlen($key) > 0)
//        {
//            $supplier_ids = DB::connection('sqlsrv')->table('vsupplier')->where('name', 'like', '%'.$key.'%')->pluck('id');
//            $purchaseorder_ids = DB::connection('sqlsrv')->table('vpurchaseorder')->where('descrip', 'like', '%'.$key.'%')->pluck('id');
//        }

        $query = User::latest();

        if (strlen($key) > 0)
        {
            $query->where(function($query) use ($key) {
                $query->where('name', 'like',  '%' . $key . '%');
            });
        }

//        if ($approvalstatus <> '')
//        {
//            if ($approvalstatus == "1")
//                $query->where('approversetting_id', '>', '0');
//            else
//                $query->where('approversetting_id', $approvalstatus);
//        }
//
//        if ($request->has('approvaldatestart') && $request->has('approvaldateend'))
//        {
//            $paymentrequestids = DB::table('paymentrequestapprovals')
//                ->select('paymentrequest_id')
//                ->groupBy('paymentrequest_id')
//                ->havingRaw('max(paymentrequestapprovals.created_at) between \'' . $request->input('approvaldatestart') . '\' and \'' . $request->input('approvaldateend') . '\'::timestamp + interval \'1D\'')
//                ->pluck('paymentrequest_id');
//            $query->whereIn('id', $paymentrequestids);
//
//
//
//
//        }

//        // paymentmethod
//        if ($request->has('paymentmethod'))
//        {
//            $query->where('paymentmethod', $request->input('paymentmethod'));
//        }
//
//        // payment status
//        // because need search hxold database, so select this condition last.
//        if ($request->has('paymentstatus'))
//        {
//            $paymentstatus = $request->input('paymentstatus');
//            if ($paymentstatus == 0)
//            {
//                $query->where('approversetting_id', '0');
//
//                $paymentrequestids = [];
//                $query->chunk(100, function($paymentrequests) use(&$paymentrequestids) {
//                    foreach ($paymentrequests as $paymentrequest) {
//                        # code...
//                        if (isset($paymentrequest->purchaseorder_hxold->payments))
//                        {
//                            if ($paymentrequest->paymentrequestapprovals->max('created_at') < $paymentrequest->purchaseorder_hxold->payments->max('create_date'))
//                                array_push($paymentrequestids, $paymentrequest->id);
//                        }
//                    }
//                });
//
//                // dd($paymentrequestids);
//                $query->whereIn('id', $paymentrequestids);
//
//            }
//            elseif ($paymentstatus == -1)
//            {
//                $query->where('approversetting_id', '0');
//
//                $paymentrequestids = [];
//                $query->chunk(100, function($paymentrequests) use(&$paymentrequestids) {
//                    foreach ($paymentrequests as $paymentrequest) {
//                        # code...
//                        if (isset($paymentrequest->purchaseorder_hxold->payments))
//                        {
//                            if ($paymentrequest->paymentrequestapprovals->max('created_at') > $paymentrequest->purchaseorder_hxold->payments->max('create_date'))
//                                array_push($paymentrequestids, $paymentrequest->id);
//                        }
//                    }
//                });
//
//                $query->whereIn('id', $paymentrequestids);
//            }
//        }


        $users = $query->select('users.*')
            ->paginate(10);
//        dd($paymentrequests);

        return $users;
    }

    public function getitemsbykey($key)
    {
        $query = User::latest();
        $query->where('name', 'like', '%'.$key.'%');
        $users = $query->paginate(20);
//        $salesorders = Salesorder_hxold::where('custinfo_id', $customerid)
//            ->where(function ($query) use ($key) {
//                $query->where('number', 'like', '%'.$key.'%')
//                    ->orWhere('descrip', 'like', '%'.$key.'%');
//            })
//            ->paginate(20);
        return $users;
    }

    public function test(Request $request)
    {
        dd('aaa');
        Log::info('abcd');
    }
}
