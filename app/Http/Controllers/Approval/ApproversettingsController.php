<?php

namespace App\Http\Controllers\Approval;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Approval\Approversetting;
use App\Http\Requests\Approval\ApproversettingRequest;

class ApproversettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $approversettings = Approversetting::latest('created_at')->orderBy('approvaltype_id')->orderBy('level')->paginate(10);
        // return view('approval.approversettings.index', compact('approversettings'));
        return view('approval.approversettings.index', ['approversettings' => $approversettings]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('approval.approversettings.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ApproversettingRequest $request)
    {
        //
        $input = $request->all();
        $approversetting = Approversetting::create($input);
        
        return redirect('/approval/approversettings');
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
        $approversetting = Approversetting::findOrFail($id);
        return view('approval.approversettings.edit', compact('approversetting'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ApproversettingRequest $request, $id)
    {
        //        
        $approversetting = Approversetting::findOrFail($id);
        dd($request->all());
        $approversetting->update($request->all());
        dd($request->all());

        
        return redirect('approval/approversettings');
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
        Approversetting::destroy($id);
        return redirect('approval/approversettings');
    }
}
