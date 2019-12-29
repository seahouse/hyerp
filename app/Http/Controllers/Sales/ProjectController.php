<?php

namespace App\Http\Controllers\Sales;

use App\Models\Sales\Project_hxold;
use App\Models\System\Report;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    }

    public function mstatistics($id)
    {
        $project = Project_hxold::find($id);

        return view('sales.projects.mstatistics', compact('project'));
    }

    public function warehousedetailbyproject($id)
    {
        $report=Report::where('name','pgetWarehouseDetailByproject')->first();
        return redirect('/system/report/' . $report->id.  '/statistics/' . $report->autostatistics .'?projectid=' . $id);
    }

    public function otherwarehousedetailbyproject($id)
    {
        $report=Report::where('name','pgetOtherWarehouseDetailByproject')->first();
        return redirect('/system/report/' . $report->id.  '/statistics/' . $report->autostatistics .'?projectid=' . $id);
    }

    public function fromotherwarehousedetailbyproject($id)
    {
        $report=Report::where('name','pgetFromOtherWarehouseDetailByproject')->first();
        return redirect('/system/report/' . $report->id.  '/statistics/' . $report->autostatistics .'?projectid=' . $id);
    }

    public function leftwarehousedetailbyproject($id)
    {
        $report=Report::where('name','pgetInventoryDetailByproject')->first();
        return redirect('/system/report/' . $report->id.  '/statistics/' . $report->autostatistics .'?projectid=' . $id);
    }
}
