<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\System\User;
use App\Http\Requests\System\UserRequest;
// use Request;
use App\Http\Requests\System\UpdateUserRequest;
use App\Http\Requests\System\UpdateUserPassRequest;
use App\Models\System\Role;
use Zizaco\Entrust\Entrust;
use Illuminate\Support\Facades\Auth;

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
        return redirect('system/users');
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
}
