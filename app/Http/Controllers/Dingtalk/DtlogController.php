<?php

namespace App\Http\Controllers\Dingtalk;

use App\Models\Dingtalk\Dtlog;
use App\Models\Sales\Salesorder_hxold;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Log;

class DtlogController extends Controller
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
        $inputs = $request->all();
        $dtlogs = $this->searchrequest($request);
//        $dtlogs = Dtlog::latest('create_time')->paginate(15);
        return view('dingtalk.dtlogs.index', compact('dtlogs', 'inputs'));
    }

    public function search(Request $request)
    {
//        $key = $request->input('key');
        $inputs = $request->all();
        $dtlogs = $this->searchrequest($request);
//        $purchaseorders = Purchaseorder_hxold::whereIn('id', $paymentrequests->pluck('pohead_id'))->get();
//        $totalamount = Paymentrequest::sum('amount');

        return view('dingtalk.dtlogs.index', compact('dtlogs', 'inputs'));
    }

    public function searchrequest($request)
    {

        $query = Dtlog::latest('create_time');


        if ($request->has('createdatestart') && $request->has('createdateend'))
        {
            $query->whereRaw("DATEDIFF(DAY, create_time, '" . $request->input('createdatestart') . "') <= 0 and DATEDIFF(DAY, create_time, '" . $request->input('createdateend') . "') >=0");

        }

        if ($request->has('creator_name'))
        {
            $query->where('creator_name', $request->input('creator_name'));
        }
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
//                // dd($paymentrequestids);
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


        $dtlogs = $query->select('*')
            ->paginate(15);

        return $dtlogs;
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
        $dtlog = Dtlog::findOrFail($id);
        return view('dingtalk.dtlogs.show', compact('dtlog'));
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

    public function relate_xmjlsgrz_sohead_id(Request $request)
    {
        $query = Dtlog::latest('create_time');
        if ($request->has('createdatestart') && $request->has('createdateend'))
        {
            $query->whereRaw("DATEDIFF(DAY, create_time, '" . $request->input('createdatestart') . "') <= 0 and DATEDIFF(DAY, create_time, '" . $request->input('createdateend') . "') >=0");
        }

        $dtlogs = $query->select('*')->get();
        $count = 0;
        Log::info($dtlogs->count());
        foreach ($dtlogs as $dtlog)
        {
            if ($dtlog->template_name == '项目经理施工日志')
            {
                $updated = false;
                $dtlogitems = $dtlog->dtlogitems;
                foreach ($dtlogitems as $dtlogitem)
                {
                    if ($dtlogitem->key == '2、工程项目名称' || $dtlogitem->key == '2、工程项目名称：' || $dtlogitem->key == '工程项目名称：')
                    {
                        $soheads = Salesorder_hxold::all();
                        foreach ($soheads as $sohead)
                        {
                            if (strpos($dtlogitem->value, $sohead->number) !== false)
                            {
                                $dtlog->update(['xmjlsgrz_sohead_id' => $sohead->id]);
                                $updated = true;
                                $count++;
                                break;
                            }
                        }
                        if ($updated) break;
                    }
                }
            }
        }

        $data = [
            'errcode' => 0,
            'errmsg' => '关联成功，共关联了' . $count . '个日志。',
        ];
        return response()->json($data);
    }
}
