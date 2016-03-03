<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\System\RolePermission;
use App\Role;
use App\Permission;
use DB;
use App\Http\Requests\System\RolepermissionRequest;

class RolepermissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($roleId)
    {
        //
        $rolepermissions = RolePermission::where('role_id', $roleId)->paginate(10);
        return view('system.rolepermissions.index', compact('rolepermissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($roleId)
    {
        //
        $role = Role::findOrFail($roleId);
        $permissionIds = RolePermission::where('role_id', $roleId)->select('permission_id')->get();
        $permissionList = Permission::whereNotIn('id', $permissionIds)->select('id', DB::raw('concat(name, \' - \', display_name) as name'))->lists('name', 'id');
        if ($role != null)
            return view('system.rolepermissions.create', compact('role', 'permissionList'));
        else
            return '无此角色';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(RolepermissionRequest $request)
    {
        //
        $role = Role::findOrFail($request->input('role_id'));
        $permission = Permission::findOrFail($request->input('permission_id'));
        if ($role != null && $permission != null)
        {
//             $role->attachPermission($permission);   // another method
            $role->perms()->sync(array($permission->id));
        }
        
        return redirect('system/roles/' . $request->input('role_id') . '/permissions');
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
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($roleId, $permissionId)
    {
        //
        $role = Role::findOrFail($roleId);
        $permission = Permission::findOrFail($permissionId);
        if ($role != null && $permission != null)
            $role->detachPermission($permission);
        else
            back();
        
        return redirect('system/roles/' . $roleId . '/permissions');        
    }
}
