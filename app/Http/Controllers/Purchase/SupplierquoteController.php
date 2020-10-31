<?php

namespace App\Http\Controllers\Purchase;

use App\Models\Purchase\Poheadquote_hx;
use App\Models\Purchase\Purchaseorder_hxold;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SupplierquoteController extends Controller
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

    public function createbypohead($pohead_id)
    {
        //
        $pohead = Purchaseorder_hxold::find($pohead_id);
        $poitem = $pohead->poitems->first();
//        dd($poitem);
        $suppliers = [];
        if (isset($poitem))
            $suppliers = $poitem->suppliermaterials->pluck('supplier_name', 'supplier_id');
        return view('purchase.supplierquotes.create', compact('pohead', 'suppliers'));
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
        $inputs = $request->all();
//        dd($inputs);
        Poheadquote_hx::create($inputs);

        $poheadquotes = Poheadquote_hx::where('pohead_id', $inputs['pohead_id'])->paginate(15);
        $pohead_id = $inputs['pohead_id'];

        return redirect('purchase/purchaseorders/' . $inputs['pohead_id'] . '/supplierquotes');
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
        $poheadquote = Poheadquote_hx::find($id);
        $pohead = Purchaseorder_hxold::find($poheadquote->pohead_id);
        Poheadquote_hx::destroy($id);
        return redirect('purchase/purchaseorders/' . $pohead->id . '/supplierquotes');
    }
}
