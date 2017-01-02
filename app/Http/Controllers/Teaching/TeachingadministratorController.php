<?php

namespace App\Http\Controllers\Teaching;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Teaching\Teachingadministrator;

class TeachingadministratorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $teachingadministrators = Teachingadministrator::latest('created_at')->paginate(10);
        return view('teaching.teachingadministrator.index', compact('teachingadministrators'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('teaching.teachingadministrator.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        Teachingadministrator::create($request->all());        
        return redirect('/teaching/teachingadministrator');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $teachingadministrator = Teachingadministrator::findOrFail($id);
        return view('teaching.teachingadministrator.edit', compact('teachingadministrator'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $teachingadministrator = Teachingadministrator::findOrFail($id);
        $teachingadministrator->update($request->all());
        
        return redirect('teaching/teachingadministrator');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        Teachingadministrator::destroy($id);
        return redirect('teaching/teachingadministrator');
    }
}
