<?php

namespace App\Http\Controllers\My;

use App\Models\Sales\Receiptpayment_hxold;
use App\Models\Sales\Salesorder_hxold;
use App\Models\System\Userold;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB, Auth, Datatables;

class MyController extends Controller
{
    //
    public function bonus()
    {
        $input = request()->all();
        $items = $this->searchrequest(request());

//        $query = Receiptpayment_hxold::latest('vreceiptpayment.date');
//        $query->leftJoin('vorder', 'vorder.id', '=', 'vreceiptpayment.sohead_id');
//        $query->leftJoin('vcustomer', 'vcustomer.id', '=', 'vorder.custinfo_id');
//        $query->leftJoin('outsourcingpercent', 'outsourcingpercent.order_number', '=', 'vorder.number');
//
//        $query->whereRaw('vreceiptpayment.date between \'2018/1/1\' and \'2018/8/1\'');
//
//        if (isset(Auth::user()->userold))
//            $query->where('vorder.salesmanager_id', Auth::user()->userold->user_hxold_id);
//
//        $items = $query->select('vreceiptpayment.id', 'vreceiptpayment.date', 'vreceiptpayment.amount', 'vreceiptpayment.sohead_id', 'vorder.number as order_number', 'vorder.amount as order_amount', 'vorder.salesmanager', 'vorder.bonusfactor as order_bonusfactor',
//            'vcustomer.name as customer_name',
//            DB::raw('100-outsourcingpercent.[percent] as profitpercent'), DB::raw('dbo.getSoheadAmountPaid(vreceiptpayment.sohead_id) as soheadamountpaid'),
//            DB::raw('vreceiptpayment.amount * vorder.bonusfactor * vorder.bonusproportion * 10000 as bonus'))
//            ->paginate(10);
//        dd($items);
        return view('my.bonus.index', compact('items', 'input'));
    }

    public function search(Request $request)
    {
        $inputs = $request->all();
        $paymentrequests = $this->searchrequest($request);
        $purchaseorders = Purchaseorder_hxold::whereIn('id', $paymentrequests->pluck('pohead_id'))->get();
        $totalamount = Paymentrequest::sum('amount');

        return view('approval.paymentrequests.index', compact('paymentrequests', 'key', 'inputs', 'purchaseorders', 'totalamount'));
    }

    public function searchrequest($request)
    {
//        $key = $request->input('key');
//        $approvalstatus = $request->input('approvalstatus');
//
//        $supplier_ids = [];
//        $purchaseorder_ids = [];
//        if (strlen($key) > 0)
//        {
//            $supplier_ids = DB::connection('sqlsrv')->table('vsupplier')->where('name', 'like', '%'.$key.'%')->pluck('id');
//            $purchaseorder_ids = DB::connection('sqlsrv')->table('vpurchaseorder')->where('descrip', 'like', '%'.$key.'%')->pluck('id');
//        }

        $query = Receiptpayment_hxold::latest('vreceiptpayment.date');
        $query->leftJoin('vorder', 'vorder.id', '=', 'vreceiptpayment.sohead_id');
        $query->leftJoin('vcustomer', 'vcustomer.id', '=', 'vorder.custinfo_id');
        $query->leftJoin('outsourcingpercent', 'outsourcingpercent.order_number', '=', 'vorder.number');



        if (isset(Auth::user()->userold))
        {
            $query->where('vorder.salesmanager_id', 15);
//            $query->where('vorder.salesmanager_id', Auth::user()->userold->user_hxold_id);
        }

//        if (strlen($key) > 0)
//        {
//            $query->where(function($query) use ($supplier_ids, $purchaseorder_ids) {
//                $query->whereIn('supplier_id', $supplier_ids)
//                    ->orWhereIn('pohead_id', $purchaseorder_ids);
//            });
//        }

//        if ($approvalstatus <> '')
//        {
//            if ($approvalstatus == "1")
//                $query->where('approversetting_id', '>', '0');
//            else
//                $query->where('approversetting_id', $approvalstatus);
//        }

        if ($request->has('receivedatestart') && $request->has('receivedateend'))
        {
            $query->whereRaw('vreceiptpayment.date between \'' . $request->input('receivedatestart') . '\' and \'' . $request->input('receivedateend')  . '\'');
        }

//        // paymentmethod
//        if ($request->has('paymentmethod'))
//        {
//            $query->where('paymentmethod', $request->input('paymentmethod'));
//        }
//
//        // payment status
//        // because need search hxold database, so select this condition last.
//        if ($request->has('paymentstatus'))
//        {
//            $paymentstatus = $request->input('paymentstatus');
//            if ($paymentstatus == 0)
//            {
//                $query->where('approversetting_id', '0');
//
//                $paymentrequestids = [];
//                $query->chunk(100, function($paymentrequests) use(&$paymentrequestids) {
//                    foreach ($paymentrequests as $paymentrequest) {
//                        # code...
//                        if (isset($paymentrequest->purchaseorder_hxold->payments))
//                        {
//                            if ($paymentrequest->paymentrequestapprovals->max('created_at') < $paymentrequest->purchaseorder_hxold->payments->max('create_date'))
//                                array_push($paymentrequestids, $paymentrequest->id);
//                        }
//                    }
//                });
//
//                $query->whereIn('id', $paymentrequestids);
//
//            }
//            elseif ($paymentstatus == -1)
//            {
//                $query->where('approversetting_id', '0');
//
//                $paymentrequestids = [];
//                $query->chunk(100, function($paymentrequests) use(&$paymentrequestids) {
//                    foreach ($paymentrequests as $paymentrequest) {
//                        # code...
//                        if (isset($paymentrequest->purchaseorder_hxold->payments))
//                        {
//                            if ($paymentrequest->paymentrequestapprovals->max('created_at') > $paymentrequest->purchaseorder_hxold->payments->max('create_date'))
//                                array_push($paymentrequestids, $paymentrequest->id);
//                        }
//                    }
//                });
//
//                $query->whereIn('id', $paymentrequestids);
//            }
//        }

//        $items = $query->select('vreceiptpayment.id', 'vreceiptpayment.date', 'vreceiptpayment.amount', 'vreceiptpayment.sohead_id', 'vorder.number as order_number', 'vorder.amount as order_amount', 'vorder.salesmanager', 'vorder.bonusfactor as order_bonusfactor',
//            'vcustomer.name as customer_name',
//            DB::raw('100-outsourcingpercent.[percent] as profitpercent'), DB::raw('dbo.getSoheadAmountPaid(vreceiptpayment.sohead_id) as soheadamountpaid'),
//            DB::raw('isnull(vreceiptpayment.amount * vorder.bonusfactor * dbo.getAmountpertenthousandBySohead(vorder.id), 0.0) as bonus'))
//            ->paginate(10);

        $items = $query->select('vreceiptpayment.id', 'vreceiptpayment.date', 'vreceiptpayment.amount', 'vreceiptpayment.sohead_id', 'vorder.number as order_number', 'vorder.amount as order_amount', 'vorder.salesmanager', 'vorder.bonusfactor as order_bonusfactor',
            'vcustomer.name as customer_name',
            DB::raw('100-outsourcingpercent.[percent] as profitpercent'), DB::raw('dbo.getSoheadAmountPaid(vreceiptpayment.sohead_id) as soheadamountpaid'))
            ->paginate(10);

        return $items;
    }

    public function bonusbyorder()
    {
        $input = request()->all();
//
//        $query = Salesorder_hxold::latest('vorder.orderdate');
//        $query->leftJoin('vcustomer', 'vcustomer.id', '=', 'vorder.custinfo_id');
//        $query->leftJoin('outsourcingpercent', 'outsourcingpercent.order_number', '=', 'vorder.number');
//
//
//        if (isset(Auth::user()->userold))
//            $query->where('vorder.salesmanager_id', 15);
//
//        $items = $query->select('vorder.*',
//            'vcustomer.name as customer_name')
//            ->paginate(10);

        return view('my.bonus.index_byorder', compact('input'));
    }

    public function indexjsonbyorder()
    {
        $query = Salesorder_hxold::whereRaw('1=1');
        $query->leftJoin('vcustomer', 'vcustomer.id', '=', 'vorder.custinfo_id');
        $query->leftJoin('outsourcingpercent', 'outsourcingpercent.order_number', '=', 'vorder.number');

//        $query->whereRaw('vreceiptpayment.date between \'2018/1/1\' and \'2018/8/1\'');

//        if (isset(Auth::user()->userold))
//            $query->where('vorder.salesmanager_id', 15);

//        $items = $query->select('vorder.*',
//            'vcustomer.name as customer_name')
//            ->paginate(10);

        return Datatables::of($query->select('vorder.*', DB::raw('(select SUM(amount) from vreceiptpayment where sohead_id=vorder.id) as amountperiod'),
            'vcustomer.name as customer_name'))
            ->addColumn('bonusfactor', function (Salesorder_hxold $sohead) {
                return $sohead->getBonusfactorByPolicy() * 100.0 . '%';
            })
            ->addColumn('bonus', function (Salesorder_hxold $sohead) {
                return $sohead->receiptpayments->sum('amount') * $sohead->getBonusfactorByPolicy() * array_first($sohead->getAmountpertenthousandBySohead())->amountpertenthousandbysohead;
            })
            ->addColumn('bonuspaid', function (Salesorder_hxold $sohead) {
                return $sohead->bonuspayments->sum('amount');
            })
            ->addColumn('paybonus', function (Salesorder_hxold $sohead) {
                return '<a href="'. url('sales/' . $sohead->id . '/bonuspayment/create') .'" target="_blank" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> 支付</a>';
            })->make(true);

//        $paymentrequests = Paymentrequest::latest('created_at')->paginate(10);
//        dd($paymentrequests);
//        $data = [
//            'draw' => 1,
//            'recordsTotal'  => 300,
//            'recordsFiltered'   =>300,
//        ];
//        return json_encode($data);
    }

    public function detailjsonbyorder($sohead_id)
    {
        $query = Receiptpayment_hxold::whereRaw('1=1');
        $query->where('sohead_id', $sohead_id);
//        $query->leftJoin('vcustomer', 'vcustomer.id', '=', 'vorder.custinfo_id');
//        $query->leftJoin('outsourcingpercent', 'outsourcingpercent.order_number', '=', 'vorder.number');

//        $query->whereRaw('vreceiptpayment.date between \'2018/1/1\' and \'2018/8/1\'');


//        $items = $query->select('vorder.*',
//            'vcustomer.name as customer_name')
//            ->paginate(10);

        return Datatables::of($query->select('vreceiptpayment.*', Db::raw('convert(varchar(100), vreceiptpayment.date, 23) as receiptdate')))
            ->addColumn('bonusfactor', function (Receiptpayment_hxold $receiptpayment) {
                return $receiptpayment->sohead->getBonusfactorByPolicy() * 100.0 . '%';
            })
            ->addColumn('bonus', function (Receiptpayment_hxold $receiptpayment) {
                return $receiptpayment->amount * $receiptpayment->sohead->getBonusfactorByPolicy() * array_first($receiptpayment->sohead->getAmountpertenthousandBySohead())->amountpertenthousandbysohead;
            })->make(true);
    }
}
