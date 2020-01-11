<?php

namespace App\Http\Controllers\My;

use App\Models\Sales\Receiptpayment_hxold;
use App\Models\Sales\Salesorder_hxold;
use App\Models\System\Employee_hxold;
use App\Models\System\Userold;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB, Auth, Datatables, Log, Excel;

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
            $query->whereRaw('vreceiptpayment.record_at between \'' . $request->input('receivedatestart') . '\' and \'' . $request->input('receivedateend')  . '\'');
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

    public function indexjsonbyorder(Request $request, $sohead_id = 0, $salesmanager_id = 0)
    {
        $query = Salesorder_hxold::whereRaw('1=1');
        $query->leftJoin('vcustomer', 'vcustomer.id', '=', 'vorder.custinfo_id');
        $query->leftJoin('outsourcingpercent', 'outsourcingpercent.order_number', '=', 'vorder.number');

        if ($sohead_id > 0)
            $query->whereRaw('vorder.id=' . $sohead_id);
        if ($salesmanager_id > 0)
            $query->where('vorder.salesmanager_id', $salesmanager_id);

//        $input = $request->all();
//        Log::info($request->get('salesmanager'));

        if ($request->has('receivedatestart') && $request->has('receivedateend'))
        {
            $query->whereRaw("(select SUM(amount) from vreceiptpayment where vreceiptpayment.sohead_id=vorder.id  and vreceiptpayment.record_at between '" . $request->get('receivedatestart') . "' and '" . $request->get('receivedateend') . "')>0");
        }
        else
            $query->whereRaw('(select SUM(amount) from vreceiptpayment where vreceiptpayment.sohead_id=vorder.id)>0');


//        if (isset(Auth::user()->userold))
//            $query->where('vorder.salesmanager_id', 15);


        return Datatables::of($query->select('vorder.*', DB::raw('(select SUM(amount) from vreceiptpayment where sohead_id=vorder.id) as amountperiod'),
            'vcustomer.name as customer_name'))
            ->filter(function ($query) use ($request) {
                if ($request->has('salesmanager') && strlen($request->get('salesmanager')) > 0) {
                    $query->where('vorder.salesmanager', "{$request->get('salesmanager')}");
                }

//                if ($request->has('email')) {
//                    $query->where('email', 'like', "%{$request->get('email')}%");
//                }
            })
            ->addColumn('receiptpercent', function (Salesorder_hxold $sohead) {
                if ($sohead->amount > 0.0)
                    return number_format($sohead->receiptpayments->sum('amount') / $sohead->amount * 100.0, 2) . "%";
                else
                    return "-";
            })
            ->addColumn('receiptpercent_excel', function (Salesorder_hxold $sohead) {
                if ($sohead->amount > 0.0)
                    return number_format($sohead->receiptpayments->sum('amount') / $sohead->amount, 4);
                else
                    return "-";
            })
            ->addColumn('amountperiod2', function (Salesorder_hxold $sohead) use ($request) {
                if ($request->has('receivedatestart') && $request->has('receivedateend'))
                {
                    return $sohead->receiptpayments->sum(function ($receiptpayment) use ($request) {
                        if (Carbon::parse($receiptpayment->record_at)->gte(Carbon::parse($request->get('receivedatestart'))) && Carbon::parse($receiptpayment->record_at)->lte(Carbon::parse($request->get('receivedateend'))))
                            return $receiptpayment->amount;
                        else
                            return 0.0;

                        if ($receiptpayment->date >= $request->get('receivedatestart') && $receiptpayment->date <= $request->get('receivedateend'))
                            return $receiptpayment->amount;
                        else
                            return 0.0;
                    });
                }
                else
                    return $sohead->receiptpayments->sum('amount');
            })
            ->addColumn('bonusfactor', function (Salesorder_hxold $sohead) use ($request) {
                if ($request->has('receivedatestart') && $request->has('receivedateend'))
                    return $sohead->getBonusfactorByPolicy($request->get('receivedateend')) * 100.0 . '%';
                else
                    return $sohead->getBonusfactorByPolicy() * 100.0 . '%';
            })
            ->addColumn('bonusfactor_excel', function (Salesorder_hxold $sohead) use ($request) {
                if ($request->has('receivedatestart') && $request->has('receivedateend'))
                    return $sohead->getBonusfactorByPolicy($request->get('receivedateend'));
                else
                    return $sohead->getBonusfactorByPolicy();
            })
            ->addColumn('bonusfactortype', function (Salesorder_hxold $sohead) {
                return $sohead->bonusfactor > 0.0 ? "手工" : "自动";
            })
            ->addColumn('bonus', function (Salesorder_hxold $sohead) use ($request) {
                if ($request->has('receivedatestart') && $request->has('receivedateend'))
                {
                    return $sohead->receiptpayments->sum(function ($receiptpayment) use ($request, $sohead) {
                        if (Carbon::parse($receiptpayment->record_at)->gte(Carbon::parse($request->get('receivedatestart'))) && Carbon::parse($receiptpayment->record_at)->lte(Carbon::parse($request->get('receivedateend'))))
                            return $receiptpayment->amount * $sohead->getBonusfactorByPolicy($request->get('receivedateend')) * array_first($sohead->getAmountpertenthousandBySohead())->amountpertenthousandbysohead;
                        else
                            return 0.0;

                        if ($receiptpayment->date >= $request->get('receivedatestart') && $receiptpayment->date <= $request->get('receivedateend'))
                            return $receiptpayment->amount * $sohead->getBonusfactorByPolicy($request->get('receivedateend')) * array_first($sohead->getAmountpertenthousandBySohead())->amountpertenthousandbysohead;
                        else
                            return 0.0;
                    });
                }
                else
                    return $sohead->receiptpayments->sum('amount') * $sohead->getBonusfactorByPolicy() * array_first($sohead->getAmountpertenthousandBySohead())->amountpertenthousandbysohead;
            })
            ->addColumn('bonuspaid', function (Salesorder_hxold $sohead) {
                return $sohead->bonuspayments->sum('amount');
            })
            ->addColumn('bonusforpay', function (Salesorder_hxold $sohead) use ($request) {
                if ($request->has('receivedateend'))
                {
                    return $sohead->receiptpayments->sum(function ($receiptpayment) use ($request, $sohead) {
                        if (Carbon::parse($receiptpayment->record_at)->lte(Carbon::parse($request->get('receivedateend'))))
                            return $receiptpayment->amount * $sohead->getBonusfactorByPolicy($request->get('receivedateend')) * array_first($sohead->getAmountpertenthousandBySohead())->amountpertenthousandbysohead;
                        else
                            return 0.0;
                    }) - $sohead->bonuspayments->sum('amount');
                }
                else
                    return $sohead->receiptpayments->sum('amount') * $sohead->getBonusfactorByPolicy() * array_first($sohead->getAmountpertenthousandBySohead())->amountpertenthousandbysohead - $sohead->bonuspayments->sum('amount');
            })
            ->addColumn('paybonus', function (Salesorder_hxold $sohead) {
                return '<a href="'. url('sales/' . $sohead->id . '/bonuspayment/create') .'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> 支付</a>';
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

    public function detailjsonbyorder(Request $request, $sohead_id)
    {
        $query = Receiptpayment_hxold::whereRaw('1=1');
        $query->where('sohead_id', $sohead_id);
//        $query->leftJoin('vcustomer', 'vcustomer.id', '=', 'vorder.custinfo_id');
//        $query->leftJoin('outsourcingpercent', 'outsourcingpercent.order_number', '=', 'vorder.number');

//        $query->whereRaw('vreceiptpayment.date between \'2018/1/1\' and \'2018/8/1\'');


//        $items = $query->select('vorder.*',
//            'vcustomer.name as customer_name')
//            ->paginate(10);

        return Datatables::of($query->select('vreceiptpayment.*', Db::raw('convert(varchar(100), vreceiptpayment.date, 23) as receiptdate'), Db::raw('convert(varchar(100), vreceiptpayment.record_at, 23) as recorddate')))
            ->filter(function ($query) use ($request) {
                if ($request->has('receivedatestart') && $request->has('receivedateend')) {
                    $query->whereRaw('vreceiptpayment.record_at between \'' . $request->get('receivedatestart') . '\' and \'' . $request->get('receivedateend') . '\'');
                }
            })
            ->addColumn('bonusfactor', function (Receiptpayment_hxold $receiptpayment) {
                return $receiptpayment->sohead->getBonusfactorByPolicy() * 100.0 . '%';
            })
            ->addColumn('bonusfactor_excel', function (Receiptpayment_hxold $receiptpayment) {
                return $receiptpayment->sohead->getBonusfactorByPolicy();
            })
            ->addColumn('bonus', function (Receiptpayment_hxold $receiptpayment) {
                return $receiptpayment->amount * $receiptpayment->sohead->getBonusfactorByPolicy() * array_first($receiptpayment->sohead->getAmountpertenthousandBySohead())->amountpertenthousandbysohead;
            })
            ->addColumn('bonusforpay', function (Receiptpayment_hxold $receiptpayment) use ($request) {
                $sohead = $receiptpayment->sohead;
                if ($request->has('receivedateend'))
                {
                    return $sohead->receiptpayments->sum(function ($receiptpayment) use ($request, $sohead) {
                            if (Carbon::parse($receiptpayment->record_at)->lte(Carbon::parse($request->get('receivedateend'))))
                                return $receiptpayment->amount * $sohead->getBonusfactorByPolicy($request->get('receivedateend')) * array_first($sohead->getAmountpertenthousandBySohead())->amountpertenthousandbysohead;
                            else
                                return 0.0;
                        }) - $sohead->bonuspayments->sum('amount');
                }
                else
                    return $sohead->receiptpayments->sum('amount') * $sohead->getBonusfactorByPolicy() * array_first($sohead->getAmountpertenthousandBySohead())->amountpertenthousandbysohead - $sohead->bonuspayments->sum('amount');
            })->make(true);
    }

    public function bonusbysalesmanager()
    {
        return view('my.bonus.index_bonusbysalesmanager');
    }

    public function indexjsonbysalesmanager(Request $request)
    {
        $query = Employee_hxold::where('dept_id', 3)
            ->orWhere('id', 8)              // WuHL
            ->orWhere('id', 16)             // LiY
        ;
//        $query->leftJoin('vcustomer', 'vcustomer.id', '=', 'vorder.custinfo_id');
//        $query->leftJoin('outsourcingpercent', 'outsourcingpercent.order_number', '=', 'vorder.number');


        return Datatables::of($query->select('vemployee.*'))
//            ->filter(function ($query) use ($request) {
//                if ($request->has('salesmanager') && strlen($request->get('salesmanager')) > 0) {
//                    $query->where('vorder.salesmanager', "{$request->get('salesmanager')}");
//                }
//
////                if ($request->has('email')) {
////                    $query->where('email', 'like', "%{$request->get('email')}%");
////                }
//            })
            ->addColumn('orderamounttotal', function (Employee_hxold $salesmanager) use ($request) {
                return Salesorder_hxold::where('salesmanager_id', $salesmanager->id)->sum('amount');
            })
            ->addColumn('receiptamountperiod', function (Employee_hxold $salesmanager) use ($request) {
                $soheads = Salesorder_hxold::where('salesmanager_id', $salesmanager->id)->get();
                $receiptamountperiod = 0.0;
                foreach ($soheads as $sohead)
                {
                    if ($request->has('receivedatestart') && $request->has('receivedateend'))
                    {
                        $receiptamountperiod += $sohead->receiptpayments->sum(function ($receiptpayment) use ($request, &$receiptamountperiod) {
                            if (Carbon::parse($receiptpayment->record_at)->gte(Carbon::parse($request->get('receivedatestart'))) && Carbon::parse($receiptpayment->record_at)->lte(Carbon::parse($request->get('receivedateend'))))
                                $receiptamountperiod += $receiptpayment->amount;
                            else
                                $receiptamountperiod += 0.0;

//                            if ($receiptpayment->date >= $request->get('receivedatestart') && $receiptpayment->date <= $request->get('receivedateend'))
//                                $receiptamountperiod += $receiptpayment->amount;
//                            else
//                                $receiptamountperiod += 0.0;
                        });
                    }
                    else
                        $receiptamountperiod += $sohead->receiptpayments->sum('amount');
                }
                return $receiptamountperiod;
            })
            ->addColumn('bonus', function (Employee_hxold $salesmanager) use ($request) {
                $soheads = Salesorder_hxold::where('salesmanager_id', $salesmanager->id)->get();
                $bonus = 0.0;
                foreach ($soheads as $sohead)
                {
                    if ($request->has('receivedatestart') && $request->has('receivedateend'))
                    {
                        $bonus += $sohead->receiptpayments->sum(function ($receiptpayment) use ($request, $sohead, &$bonus) {
                            if (Carbon::parse($receiptpayment->record_at)->gte(Carbon::parse($request->get('receivedatestart'))) && Carbon::parse($receiptpayment->record_at)->lte(Carbon::parse($request->get('receivedateend'))))
                                $bonus += $receiptpayment->amount * $sohead->getBonusfactorByPolicy($request->get('receivedateend')) * array_first($sohead->getAmountpertenthousandBySohead())->amountpertenthousandbysohead;
                            else
                                $bonus += 0.0;

//                            if ($receiptpayment->date >= $request->get('receivedatestart') && $receiptpayment->date <= $request->get('receivedateend'))
//                                $bonus += $receiptpayment->amount * $sohead->getBonusfactorByPolicy($request->get('receivedateend')) * array_first($sohead->getAmountpertenthousandBySohead())->amountpertenthousandbysohead;
//                            else
//                                $bonus += 0.0;
                        });
                    }
                    else
                        $bonus += $sohead->receiptpayments->sum('amount') * $sohead->getBonusfactorByPolicy() * array_first($sohead->getAmountpertenthousandBySohead())->amountpertenthousandbysohead;
                }
                return $bonus;
            })
            ->addColumn('bonuspaid', function (Employee_hxold $salesmanager) use ($request) {
                $soheads = Salesorder_hxold::where('salesmanager_id', $salesmanager->id)->get();
                $bonuspaid = 0.0;
                foreach ($soheads as $sohead)
                {
                    $bonuspaid += $sohead->bonuspayments->sum('amount');
                }
                return $bonuspaid;
            })
            ->addColumn('bonuspaidperiod', function (Employee_hxold $salesmanager) use ($request) {
                $soheads = Salesorder_hxold::where('salesmanager_id', $salesmanager->id)->get();
                $bonuspaid = 0.0;
                foreach ($soheads as $sohead)
                {
                    if ($request->has('receivedatestart') && $request->has('receivedateend'))
                    {
                        $bonuspaid += $sohead->bonuspayments->sum(function ($bonuspayment) use ($request, &$bonuspaid) {
                            if (Carbon::parse($bonuspayment->paymentdate)->gte(Carbon::parse($request->get('receivedatestart'))) && Carbon::parse($bonuspayment->paymentdate)->lte(Carbon::parse($request->get('receivedateend'))))
                                $bonuspaid += $bonuspayment->amount;
                            else
                                $bonuspaid += 0.0;

//                            if ($bonuspayment->paymentdate >= $request->get('receivedatestart') && $bonuspayment->paymentdate <= $request->get('receivedateend'))
//                                $bonuspaid += $bonuspayment->amount;
//                            else
//                                $bonuspaid += 0.0;
                        });
                    }
                    else
                        $bonuspaid += $sohead->bonuspayments->sum('amount');
                }
                return $bonuspaid;
            })
            ->make(true);

    }

    public function bonusbytechdept()
    {
        return view('my.bonus.index_bonusbytechdept');
    }

    public function indexjsonbytechdept(Request $request)
    {
        $query = Employee_hxold::where('dept_id', 4)
            ->orWhere('dept_id', 36)
        ;
//        $query->leftJoin('vcustomer', 'vcustomer.id', '=', 'vorder.custinfo_id');
//        $query->leftJoin('outsourcingpercent', 'outsourcingpercent.order_number', '=', 'vorder.number');


        return Datatables::of($query->select('vemployee.*'))
//            ->filter(function ($query) use ($request) {
//                if ($request->has('salesmanager') && strlen($request->get('salesmanager')) > 0) {
//                    $query->where('vorder.salesmanager', "{$request->get('salesmanager')}");
//                }
//
////                if ($request->has('email')) {
////                    $query->where('email', 'like', "%{$request->get('email')}%");
////                }
//            })
            ->addColumn('orderamounttotal', function (Employee_hxold $designer_tech) use ($request) {
                return Salesorder_hxold::where('designer_tech1_id', $designer_tech->id)->sum('amount');
            })
            ->addColumn('receiptamountperiod', function (Employee_hxold $designer_tech) use ($request) {
                $soheads = Salesorder_hxold::where('designer_tech1_id', $designer_tech->id)->get();
                $receiptamountperiod = 0.0;
                foreach ($soheads as $sohead)
                {
                    if ($request->has('receivedatestart') && $request->has('receivedateend'))
                    {
                        $receiptamountperiod += $sohead->receiptpayments->sum(function ($receiptpayment) use ($request, &$receiptamountperiod) {
                            if ($receiptpayment->record_at >= $request->get('receivedatestart') && $receiptpayment->record_at <= $request->get('receivedateend'))
                                $receiptamountperiod += $receiptpayment->amount;
                            else
                                $receiptamountperiod += 0.0;
                        });
                    }
                    else
                        $receiptamountperiod += $sohead->receiptpayments->sum('amount');
                }
                return $receiptamountperiod;
            })
            ->addColumn('bonus', function (Employee_hxold $designer_tech) use ($request) {
                $soheads = Salesorder_hxold::where('designer_tech1_id', $designer_tech->id)->get();
                $bonus = 0.0;
                foreach ($soheads as $sohead)
                {
                    if ($request->has('receivedatestart') && $request->has('receivedateend'))
                    {
                        $bonus += $sohead->receiptpayments->sum(function ($receiptpayment) use ($request, $sohead, &$bonus) {
                            if ($receiptpayment->record_at >= $request->get('receivedatestart') && $receiptpayment->record_at <= $request->get('receivedateend'))
                                $bonus += $receiptpayment->amount * $sohead->getBonusfactorByPolicy($request->get('receivedateend')) * $sohead->getAmountpertenthousandBySoheadTech();
                            else
                                $bonus += 0.0;
                        });
                    }
                    else
                        $bonus += $sohead->receiptpayments->sum('amount') * $sohead->getBonusfactorByPolicy() * $sohead->getAmountpertenthousandBySoheadTech();
                }
                return $bonus;
            })
            ->addColumn('bonuspaid', function (Employee_hxold $designer_tech) use ($request) {
                $soheads = Salesorder_hxold::where('designer_tech1_id', $designer_tech->id)->get();
                $bonuspaid = 0.0;
                foreach ($soheads as $sohead)
                {
                    $bonuspaid += $sohead->bonuspayments->sum('amount');
                }
                return $bonuspaid;
            })
            ->addColumn('bonuspaidperiod', function (Employee_hxold $designer_tech) use ($request) {
                $soheads = Salesorder_hxold::where('designer_tech1_id', $designer_tech->id)->get();
                $bonuspaid = 0.0;
                foreach ($soheads as $sohead)
                {
                    if ($request->has('receivedatestart') && $request->has('receivedateend'))
                    {
                        $bonuspaid += $sohead->bonuspayments->sum(function ($bonuspayment) use ($request, &$bonuspaid) {
                            if ($bonuspayment->paymentdate >= $request->get('receivedatestart') && $bonuspayment->paymentdate <= $request->get('receivedateend'))
                                $bonuspaid += $bonuspayment->amount;
                            else
                                $bonuspaid += 0.0;
                        });
                    }
                    else
                        $bonuspaid += $sohead->bonuspayments->sum('amount');
                }
                return $bonuspaid;
            })
            ->make(true);

    }

    /**
     * export to excel/pdf.
     *
     * @return \Illuminate\Http\Response
     */
    public function byorderexport(Request $request)
    {
        //
        $filename = "销售人员奖金明细_按订单";
        Excel::create($filename, function($excel) use ($request, $filename) {
            $sohead_ids = [];
            if ($request->has('salesmanager') && $request->get('sohead_id') > 0)
                array_push($sohead_ids, $request->get('sohead_id'));
            else
            {
                $sohead_ids = Salesorder_hxold::pluck('id');
            }
            foreach ($sohead_ids as $sohead_id)
            {
                $sheetname = "Sheetname" . $sohead_id;
                $sohead = Salesorder_hxold::find($sohead_id);
                if ($sohead)
                    $sheetname = $sohead->projectjc;

                $soheadbonus = $this->indexjsonbyorder($request, $sohead_id);
                $soheadbonusArray = $soheadbonus->getData(true)["data"];
//                dd($soheadbonusArray);
                $soheadbonusdetail = $this->detailjsonbyorder($request, $sohead_id);
                $soheadbonusdetailArray = $soheadbonusdetail->getData(true)["data"];
                if (count($soheadbonusArray) == 1 && count($soheadbonusdetailArray) > 0)
                {
//                    dd($soheadbonusArray);
                    $excel->sheet($sheetname, function($sheet) use ($request, $sohead_id, $soheadbonusArray, $soheadbonusdetailArray) {
                        // Sheet manipulation
                        $data = [];
                        $tonnagetotal_pppayment_youqi = 0.0;
                        $tonnagetotal_pppayment_rengong = 0.0;
                        $tonnagetotal_pppayment_maohan = 0.0;
                        $tonnagetotal_out = 0.0;
                        $tonnagetotal_in = 0.0;

                        foreach ($soheadbonusdetailArray as $value)
                        {
                            $temp = [];
                            $temp['订单编号']          = $soheadbonusArray[0]['number'];
                            $temp['订单名称']          = $soheadbonusArray[0]['projectjc'];
                            $temp['订单金额']          = (double)$soheadbonusArray[0]['amount'];
                            $temp['销售经理']          = $soheadbonusArray[0]['salesmanager'];
                            $temp['收款']             = (double)$soheadbonusArray[0]['receiptpercent_excel'];

                            $temp['录入日期']          = $value['recorddate'];
                            $temp['收款金额']          = (double)$value['amount'];
                            $temp['奖金系数']          = (double)$value['bonusfactor_excel'];
                            $temp['系数类别']          = $soheadbonusArray[0]['bonusfactortype'];
                            $temp['区间奖金']          = $value['bonus'];
                            $temp['当前应发']          = $value['bonusforpay'];


//                            $temp['结算日期']             = '';
//                            $temp['抛丸']           = '';
//                            $temp['油漆']            = '';
//                            $temp['人工']          = '';
//                            $temp['铆焊']           = '';
//                            $temp['结算制作公司']        = '';
//                            $temp['结算制作概述']       = '';
//                            $temp['结算支付日期']              = '';
//                            $temp['结算申请人']                = '';
//                            $temp['结算吨位']                  = '';
                            array_push($data, $temp);
//                            $tonnagetotal_issuedrawing += $value['tonnage'];

//                            dd($temp);
                        }

//                        $param = "@orderid=" . $sohead_id;
//                        $sohead_outitems = DB::connection('sqlsrv')->select(' pGetOrderOutHeight ' . $param);
//                        if (count($sohead_outitems) > 0 && isset($sohead_outitems[0]))
//                            $tonnagetotal_out = $sohead_outitems[0]->heights / 1000.0;
//
//                        $sohead_initems = DB::connection('sqlsrv')->select(' pGetOrderInHeight ' . $param);
//                        if (count($sohead_initems) > 0 && isset($sohead_initems[0]))
//                            $tonnagetotal_in = $sohead_initems[0]->heights / 1000.0;

                        $sheet->freezeFirstRow();
                        $sheet->setColumnFormat(array(
                            'E'     => '0.00%',
                            'H'     => '0.00%',
                        ));
                        $sheet->fromArray($data);

//                        $totalrowcolor = "#00FF00";       // green
//                        if ($tonnagetotal_issuedrawing < $tonnagetotal_mcitempurchase || $tonnagetotal_issuedrawing < $tonnagetotal_pppayment)
//                            $totalrowcolor = "#FF0000"; // red
//                        $sheet->appendRow([$tonnagetotal_issuedrawing, $tonnagetotal_mcitempurchase,
//                            $tonnagetotal_pppayment . "（其中抛丸" . $tonnagetotal_pppayment_paowan . "，油漆" . $tonnagetotal_pppayment_youqi . "，人工" . $tonnagetotal_pppayment_rengong . "，铆焊" . $tonnagetotal_pppayment_maohan . "）",
//                            "领用" . $tonnagetotal_out, "入库" . $tonnagetotal_in
//                        ]);
//                        $sheet->row(count($data) + 2, function ($row) use ($totalrowcolor) {
//                            $row->setBackground($totalrowcolor);
//                        });
                    });
                }


            }

            // Set the title
            $excel->setTitle($filename);

            // Chain the setters
            $excel->setCreator('HXERP')
                ->setCompany('Huaxing East');

            // Call them separately
//            $excel->setDescription('A demonstration to change the file properties');

        })->export('xlsx');

    }

    public function byorderexport2(Request $request)
    {
        //
        $filename = "销售人员奖金明细_按销售经理";
        Excel::create($filename, function($excel) use ($request, $filename) {
            $salesmanagers = [];
            if ($request->has('salesmanager') && $request->get('sohead_id') > 0)
                array_push($sohead_ids, $request->get('sohead_id'));
            else
            {
                $salesmanagers = Salesorder_hxold::distinct()->pluck('salesmanager', 'salesmanager_id');
            }
            foreach ($salesmanagers as $key => $salesmanager)
            {
                $sheetname = "Sheetname" . $key;
                if (strlen($salesmanager) > 0)
                    $sheetname = $salesmanager;

                $soheadbonus = $this->indexjsonbyorder($request, 0, $key);
                $soheadbonusArray = $soheadbonus->getData(true)["data"];
                if (count($soheadbonusArray) > 0)
                {
                    $excel->sheet($sheetname, function($sheet) use ($request, $soheadbonusArray) {
                        // Sheet manipulation
                        $data = [];
                        $tonnagetotal_pppayment_youqi = 0.0;
                        $tonnagetotal_pppayment_rengong = 0.0;
                        $tonnagetotal_pppayment_maohan = 0.0;
                        $tonnagetotal_out = 0.0;
                        $tonnagetotal_in = 0.0;

                        foreach ($soheadbonusArray as $soheadbonus)
                        {
                            $soheadbonusdetail = $this->detailjsonbyorder($request, $soheadbonus['id']);
                            $soheadbonusdetailArray = $soheadbonusdetail->getData(true)["data"];
                            if (count($soheadbonusdetailArray) > 0)
                            {
//                                dd($soheadbonusdetailArray);
                                foreach ($soheadbonusdetailArray as $value)
                                {
                                    $temp = [];
                                    $temp['订单编号']          = $soheadbonus['number'];
                                    $temp['订单名称']          = $soheadbonus['projectjc'];
                                    $temp['订单金额']          = (double)$soheadbonus['amount'];
//                                    $temp['销售经理']          = $soheadbonus['salesmanager'];
                                    $temp['收款']             = (double)$soheadbonus['receiptpercent_excel'];

                                    $temp['录入日期']          = $value['recorddate'];
                                    $temp['收款金额']          = (double)$value['amount'];
                                    $temp['奖金系数']          = (double)$value['bonusfactor_excel'];
                                    $temp['系数类别']          = $soheadbonus['bonusfactortype'];
                                    $temp['区间奖金']          = $value['bonus'];
                                    $temp['当前应发']          = $value['bonusforpay'];


                                    array_push($data, $temp);
//                            $tonnagetotal_issuedrawing += $value['tonnage'];

                                }
                            }
                        }

                        $sheet->freezeFirstRow();
                        $sheet->setColumnFormat(array(
                            'D'     => '0.00%',
                            'G'     => '0.00%',
                        ));
                        $sheet->fromArray($data);
                    });
                }


            }

            if (count($salesmanagers) > 0)
            {
                $sheetname = "总表";

                $soheadbonus = $this->indexjsonbyorder($request);
                $soheadbonusArray = $soheadbonus->getData(true)["data"];
                if (count($soheadbonusArray) > 0)
                {
                    $excel->sheet($sheetname, function($sheet) use ($request, $soheadbonusArray) {
                        // Sheet manipulation
                        $data = [];

                        foreach ($soheadbonusArray as $soheadbonus)
                        {
                            $temp = [];
                            $temp['订单编号']          = $soheadbonus['number'];
                            $temp['订单名称']          = $soheadbonus['projectjc'];
                            $temp['订单金额']          = (double)$soheadbonus['amount'];
                            $temp['销售经理']          = $soheadbonus['salesmanager'];
                            $temp['收款']              = (double)$soheadbonus['receiptpercent_excel'];
                            $temp['区间收款']          = $soheadbonus['amountperiod2'];
                            $temp['奖金系数']          = (double)$soheadbonus['bonusfactor_excel'];
                            $temp['系数类别']          = $soheadbonus['bonusfactortype'];
                            $temp['区间奖金']          = $soheadbonus['bonus'];
                            $temp['当前应发']          = $soheadbonus['bonusforpay'];

                            array_push($data, $temp);

                        }


                        $sheet->freezeFirstRow();
                        $sheet->setColumnFormat(array(
                            'E'     => '0.00%',
                            'G'     => '0.00%',
                        ));
                        $sheet->fromArray($data);

                    });
                }
            }


            // Set the title
            $excel->setTitle($filename);

            // Chain the setters
            $excel->setCreator('HXERP')
                ->setCompany('Huaxing East');

            // Call them separately
//            $excel->setDescription('A demonstration to change the file properties');

        })->export('xlsx');

    }
}
