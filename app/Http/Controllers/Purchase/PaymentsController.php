<?php

namespace App\Http\Controllers\Purchase;

// use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Purchase\Payment;
use App\Models\Purchase\Purchaseorder;
use App\Http\Requests\Purchase\PaymentRequest;
use Request;
use App\Models\Purchase\Vendinfo;
use App\Models\Purchase\Purchaseorder_hxold;
use App\Models\Purchase\Payment_hxold_t;

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

    public function create_hxold($poheadid, $amount = 0.0)
    {
        //
        $purchaseorder = Purchaseorder_hxold::findOrFail($poheadid);
        return view('purchase.payments.create_hxold', compact('purchaseorder', 'amount'));
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

    public function store_hxold(Request $request, $poheadid)
    {
        //        
        $input = Request::all();
        $input['录入时间'] = \Carbon\Carbon::now();
        $payment = Payment_hxold_t::create($input);
        // return redirect('purchase/purchaseorders/' . $poheadid . '/payments');
        return '付款成功。';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function storebync(PaymentRequest $request)
    {
        //
        $input = Request::all();

        // 如果供应商不存在，则创建
        $vendorname = $input['SupplierName'];
        $vendinfo = Vendinfo::firstOrNew(['name' => $vendorname]);
        if (!$vendinfo->number)
        {
            $vendinfo->number = $vendorname;
            $vendinfo->name = $vendorname;
            $vendinfo->save();
        }

        // 如果采购订单不存在，则根据客户名称和工程名称添加新订单
        $projectname = $input['ProjectName'];
        $purchaseorder = Purchaseorder::firstOrNew(['descrip' => $projectname]);
        if (!$purchaseorder->number)
        {
            $purchaseorder->number = $projectname;
            $purchaseorder->descrip = $projectname;
            $purchaseorder->vendinfo_id = $vendinfo->id;
            $purchaseorder->save();
        }

        $payment = new Payment;
        $payment->pohead_id = $purchaseorder->id;
        $payment->amount = $input['Amount'];
        $payment->paydate = $input['HappenDate'];
        $payment->notes = $input['Remark'];
        $payment->save();

        $return = [
            'Code' => 0,
            'Description' => 'SUCCESS.'
        ];
        return json_encode($return);
        
        // $purchaseorder = Purchaseorder::findOrFail($poheadId);
        // $poitems = $purchaseorder->poitems;
        // $priceTotal = 0.0;
        // foreach ($poitems as $poitem)
        //     $priceTotal += $poitem->unitprice * $poitem->qty_ordered;
        
        // $pricePaied = Payment::where('pohead_id', $poheadId)->sum('amount');
        
        // if ($priceTotal <= $pricePaied)
        //     return '已完成付款';
        
        // $input = Request::all();
        // Payment::create($input);
        // return redirect('purchase/purchaseorders/' . $poheadId . '/payments');
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
