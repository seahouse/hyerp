<?php

namespace App\Http\Controllers\Sales;

use App\Models\Sales\Dwgbom_hx;
use App\Models\Sales\Dwgbomitems_hx;
use App\Models\Sales\Salesorder_hxold;
use App\Models\Sales\Salesorder_hxold_t;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Datatables, Log, Excel;

class SalesorderhxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $request = request();
        return $this->search($request);

//        $salesorders = Salesorder_hxold::where('status', '<>', -10)->orderBy('id', 'desc')->paginate(10);
//        return view('sales.salesorderhx.index', compact('salesorders'));
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


    public function search(Request $request)
    {
        //
//        dd($request->all());
        $inputs = $request->all();
        $key = $request->input('key');

        $query = Salesorder_hxold::where('status', '<>', -10)->orderBy('id', 'desc');
        if (strlen($key))
        {
            $query->where(function ($query) use ($key) {
                $query->where('number', 'like', '%' . $key. '%')
                    ->orWhere('descrip', 'like', '%' . $key. '%');
            });
        }

        $salesorders = $query->select()->paginate(10);
        return view('sales.salesorderhx.index', compact('salesorders', 'inputs'));
    }

    public function checktaxrateinput($id)
    {
        //
        $exitCode = Artisan::call('reminder:taxrateinput', [
//            '--debug' => true,
            '--sohead_id' => $id,
        ]);
        dd('检查完成, 请查看钉钉消息.(' . $exitCode . ')');
    }

    public function dwgbom($id)
    {
        //
        $dwgboms = Dwgbom_hx::where('sohead_id', $id)->paginate(10);
        return view('sales.salesorderhx.dwgbom', compact('dwgboms', 'id'));
    }

    public function dwgbomjson(Request $request)
    {
        $query = Dwgbom_hx::whereRaw('1=1');

        if ($request->has('sohead_id'))
        {
            $query->where('sohead_id', $request->get('sohead_id'));
        }





        return Datatables::of($query->select('dwgbom.id', 'dwgbom.bomname', 'dwgbom.created_at as create_at', 'dwgbom.updated_at as update_at'))
//            ->editColumn('created_at', 'dwgbom.created_at')
//            ->addColumn('amountperiod2', function (Salesorder_hxold $sohead) use ($request) {
//                if ($request->has('receivedatestart') && $request->has('receivedateend'))
//                {
//                    return $sohead->receiptpayments->sum(function ($receiptpayment) use ($request) {
//                        if ($receiptpayment->date >= $request->get('receivedatestart') && $receiptpayment->date <= $request->get('receivedateend'))
//                            return $receiptpayment->amount;
//                        else
//                            return 0.0;
//                    });
//                }
//                else
//                    return $sohead->receiptpayments->sum('amount');
//            })
//            ->addColumn('bonusfactor', function (Salesorder_hxold $sohead) use ($request) {
//                if ($request->has('receivedatestart') && $request->has('receivedateend'))
//                    return $sohead->getBonusfactorByPolicy($request->get('receivedateend')) * 100.0 . '%';
//                else
//                    return $sohead->getBonusfactorByPolicy() * 100.0 . '%';
//            })
//            ->addColumn('bonus', function (Salesorder_hxold $sohead) use ($request) {
//                if ($request->has('receivedatestart') && $request->has('receivedateend'))
//                {
//                    return $sohead->receiptpayments->sum(function ($receiptpayment) use ($request, $sohead) {
//                        if ($receiptpayment->date >= $request->get('receivedatestart') && $receiptpayment->date <= $request->get('receivedateend'))
//                            return $receiptpayment->amount * $sohead->getBonusfactorByPolicy($request->get('receivedateend')) * array_first($sohead->getAmountpertenthousandBySohead())->amountpertenthousandbysohead;
//                        else
//                            return 0.0;
//                    });
//                }
//                else
//                    return $sohead->receiptpayments->sum('amount') * $sohead->getBonusfactorByPolicy() * array_first($sohead->getAmountpertenthousandBySohead())->amountpertenthousandbysohead;
//            })
//            ->addColumn('bonuspaid', function (Salesorder_hxold $sohead) {
//                return $sohead->bonuspayments->sum('amount');
//            })
//            ->addColumn('paybonus', function (Salesorder_hxold $sohead) {
//                return '<a href="'. url('sales/' . $sohead->id . '/bonuspayment/create') .'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> 支付</a>';
//            })
            ->make(true);

    }

    public function dwgbomjsondetail(Request $request, $id = 0)
    {
        $query = Dwgbomitems_hx::whereRaw('1=1');
        $query->leftJoin('hxcrm2016.dbo.vgoods', 'vgoods.goods_id', '=', 'dwgbomitems.goods_id');

        if ($id > 0)
            $query->where('dwgbom_id', $id);




        return Datatables::of($query->select('dwgbomitems.*', 'vgoods.goods_name', 'vgoods.goods_spec'))
//            ->editColumn('created_at', 'dwgbom.created_at')
//            ->addColumn('amountperiod2', function (Salesorder_hxold $sohead) use ($request) {
//                if ($request->has('receivedatestart') && $request->has('receivedateend'))
//                {
//                    return $sohead->receiptpayments->sum(function ($receiptpayment) use ($request) {
//                        if ($receiptpayment->date >= $request->get('receivedatestart') && $receiptpayment->date <= $request->get('receivedateend'))
//                            return $receiptpayment->amount;
//                        else
//                            return 0.0;
//                    });
//                }
//                else
//                    return $sohead->receiptpayments->sum('amount');
//            })
//            ->addColumn('bonusfactor', function (Salesorder_hxold $sohead) use ($request) {
//                if ($request->has('receivedatestart') && $request->has('receivedateend'))
//                    return $sohead->getBonusfactorByPolicy($request->get('receivedateend')) * 100.0 . '%';
//                else
//                    return $sohead->getBonusfactorByPolicy() * 100.0 . '%';
//            })
//            ->addColumn('bonus', function (Salesorder_hxold $sohead) use ($request) {
//                if ($request->has('receivedatestart') && $request->has('receivedateend'))
//                {
//                    return $sohead->receiptpayments->sum(function ($receiptpayment) use ($request, $sohead) {
//                        if ($receiptpayment->date >= $request->get('receivedatestart') && $receiptpayment->date <= $request->get('receivedateend'))
//                            return $receiptpayment->amount * $sohead->getBonusfactorByPolicy($request->get('receivedateend')) * array_first($sohead->getAmountpertenthousandBySohead())->amountpertenthousandbysohead;
//                        else
//                            return 0.0;
//                    });
//                }
//                else
//                    return $sohead->receiptpayments->sum('amount') * $sohead->getBonusfactorByPolicy() * array_first($sohead->getAmountpertenthousandBySohead())->amountpertenthousandbysohead;
//            })
//            ->addColumn('bonuspaid', function (Salesorder_hxold $sohead) {
//                return $sohead->bonuspayments->sum('amount');
//            })
//            ->addColumn('paybonus', function (Salesorder_hxold $sohead) {
//                return '<a href="'. url('sales/' . $sohead->id . '/bonuspayment/create') .'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> 支付</a>';
//            })
            ->make(true);

    }

    public function importothercostpercent()
    {
        //
        return view('sales.salesorderhx.importothercostpercent');
    }

    public function importothercostpercentstore(Request $request)
    {
        //
        $this->validate($request, [
//            'salary_date'       => 'required',
//            'itemtype'                    => 'required',
//            'expirationdate'             => 'required',
//            'sohead_id'                   => 'required|integer|min:1',
//            'items_string'               => 'required',
//            'detailuse'               => 'required',
        ]);

        $file = $request->file('file');
//        dd($file->getRealPath());
//        $file = array_get($input,'file');
//        dd($file->public_path());
//        Log::info($request->getSession().getServletContext()->getReadPath("/xx"));


        // !! set config/excel.php
        // 'force_sheets_collection' => true,   // !!
        Log::info('import start.');
//        Excel::filter('chunk')->load($file->getRealPath())->chunk(250, function($results)
//        {
//            foreach($results as $row)
//            {
//                // do stuff
//            }
//        });
//        return redirect('basic/biddinginformations');

        echo "更新了以下内容：</br>";
        Excel::load($file->getRealPath(), function ($reader) {
            $reader->each(function ($sheet) {
                $sheet->each(function ($row) {
                    $row->each(function ($cell) {
                        $items = explode("\t", $cell);
                        if (count($items) == 3)
                        {
//                            $sohead = Salesorder_hxold_t::find($items[0]);
//                            $sohead->othercostpercent = $items[2];
//                            $sohead->save();
                            if (Salesorder_hxold_t::where('订单ID', $items[0])
                                ->update(['othercostpercent' => $items[2]]))
                            {
                                echo $cell . "</br>";
                            }
//                            $sohead = Salesorder_hxold_t::find($items[0]);
                        }
                    });
                });
            });
        });
        Log::info('import end.');
        dd('导入完成。');
        return redirect('basic/biddinginformations');
    }
}
