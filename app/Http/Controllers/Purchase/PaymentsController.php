<?php

namespace App\Http\Controllers\Purchase;

// use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Purchase\Payment;
use App\Purchase\Purchaseorder;
use App\Http\Requests\Purchase\PaymentRequest;
use Request;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($poheadId)
    {
        //
        $payments = Payment::where('pohead_id', $poheadId)->paginate(10);
        return view('purchase.payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($poheadId)
    {
        //
        $purchaseorder = Purchaseorder::findOrFail($poheadId);
        return view('purchase.payments.create', compact('purchaseorder'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(PaymentRequest $request, $poheadId)
    {
        //
        $purchaseorder = Purchaseorder::findOrFail($poheadId);
        $poitems = $purchaseorder->poitems;
        $priceTotal = 0.0;
        foreach ($poitems as $poitem)
            $priceTotal += $poitem->unitprice * $poitem->qty_ordered;
        
        $pricePaied = Payment::where('pohead_id', $poheadId)->sum('amount');
        
        if ($priceTotal <= $pricePaied)
            return '已完成付款';
        
        $input = Request::all();
        Payment::create($input);
        return redirect('purchase/purchaseorders/' . $poheadId . '/payments');
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
    public function destroy($poheadId, $paymentId)
    {
        //
        Payment::destroy($paymentId);
        return redirect('purchase/purchaseorders/' . $poheadId . '/payments');
    }
}
