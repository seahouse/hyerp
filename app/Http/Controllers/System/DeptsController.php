<?php

namespace App\Http\Controllers\System;

// use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\System\DeptRequest;
use App\System\Dept;
use Request;

class DeptsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        $depts = Dept::latest('created_at')->paginate(10);
        return view('system.depts.index', compact('depts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        return view('system.depts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(DeptRequest $request)
    {
        //
        $input = Request::all();
        Dept::create($input);
        return redirect('system/depts');
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
        $dept = Dept::findOrFail($id);
        return view('system.depts.edit', compact('dept'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(DeptRequest $request, $id)
    {
        //
        $dept = Dept::findOrFail($id);
        $dept->update($request->all());
        return redirect('system/depts');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
        Dept::destroy($id);
        return redirect('system/depts');
    }
}
