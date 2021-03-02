<?php

namespace App\Http\Controllers\Purchase;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Purchase\Voucher;
use App\Models\Purchase\Purchaseorder;
use App\Models\Purchase\Purchaseorder_hxold;
use Illuminate\Support\Facades\Auth;

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
        $this->validate($request, ['voucher_no' => 'required|unique:vouchers'], ['voucher_no.unique' => '凭证号需唯一']);

        $v = new Voucher();
        $v->ref_id = $poheadId;
        $v->ref_type = 'PO';
        $v->voucher_no = $request->get('voucher_no');
        $v->amount = $request->get('amount');
        $v->post_date = $request->get('post_date');
        $v->remark = $request->get('remark');
        $v->creator = Auth::user()->id;
        $v->save();

        return redirect("/purchase/purchaseorders/$poheadId/vouchers");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($poheadId, $id)
    {
        $voucher = Voucher::find($id);
        return view('purchase.vouchers.show', compact('voucher'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($poheadId, $id)
    {
        $voucher = Voucher::find($id);
        return view('purchase.vouchers.edit', compact('voucher'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $poheadId, $id)
    {
        $this->validate($request, ['voucher_no' => "required|unique:vouchers,voucher_no,$id"], ['voucher_no.unique' => '凭证号需唯一']);
        $voucher = Voucher::find($id);
        $voucher->voucher_no = $request->get('voucher_no');
        $voucher->amount = $request->get('amount');
        $voucher->post_date = $request->get('post_date');
        $voucher->remark = $request->get('remark');
        $voucher->updater = Auth::user()->id;
        $voucher->save();
        return redirect(route('purchase.purchaseorders.{id}.vouchers.index', ['id' => $poheadId]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($poheadId, $id)
    {
        Voucher::destroy($id);
        return back();
    }
}
