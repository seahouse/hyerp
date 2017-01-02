<?php

namespace App\Http\Controllers\Teaching;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Teaching\Teachingpoint;

class TeachingpointController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $teachingpoints = Teachingpoint::latest('created_at')->paginate(10);
        return view('teaching.teachingpoint.index', compact('teachingpoints'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('teaching.teachingpoint.create');
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
        Teachingpoint::create($request->all());        
        return redirect('/teaching/teachingpoint');
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
        $teachingpoint = Teachingpoint::findOrFail($id);
        return view('teaching.teachingpoint.edit', compact('teachingpoint'));
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
        $teachingpoint = Teachingpoint::findOrFail($id);
        $teachingpoint->update($request->all());
        
        return redirect('teaching/teachingpoint');
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
        Teachingpoint::destroy($id);
        return redirect('teaching/teachingpoint');
    }
}
