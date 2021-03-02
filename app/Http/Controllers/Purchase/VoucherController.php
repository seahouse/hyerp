<?php

namespace App\Http\Controllers\Purchase;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Purchase\Voucher;
use App\Models\Purchase\Purchaseorder;
use App\Models\Purchase\Purchaseorder_hxold;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($poheadId)
    {
        $purchaseorder = Purchaseorder_hxold::findOrFail($poheadId);
        $vouchers = Voucher::where('ref_id', $poheadId)->where('ref_type', 'PO')->paginate(10);
        return view('purchase.vouchers.index', compact('vouchers', 'purchaseorder'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($poheadId)
    {
        $purchaseorder = Purchaseorder_hxold::findOrFail($poheadId);
        // dd($purchaseorder);
        return view('purchase.vouchers.create', compact('purchaseorder'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($poheadId, Request $request)
    {
        dump($poheadId);
        dd($request);
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
}
