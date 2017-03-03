<?php

namespace App\Http\Controllers\System;

// use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

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
        $users = User::latest('created_at')->paginate(10);
        return view('system.users.index', compact('users'));
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
}
