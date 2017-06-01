<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Inventory\Rwrecord_hxold;
use App\Models\Inventory\Receiptitem_hxold;

class RwrecordController extends Controller
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

    // 显示入库明细
    public function receiptitems($id)
    {
        //
        $receiptitems = Rwrecord_hxold::findOrFail($id)->receiptitems;
//         dd($receiptitems);

        return view('inventory.receiptitems.index', compact('receiptitems'));
    }

    // 显示入库明细
    public function receiptitems_hx($id)
    {
        //
        $receiptitems = Rwrecord_hxold::findOrFail($id)->receiptitems;
//        dd($receiptitems);

        return view('inventory.receiptitems.index_hx', compact('receiptitems'));
    }
}
