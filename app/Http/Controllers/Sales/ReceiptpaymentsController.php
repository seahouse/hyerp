<?php

namespace App\Http\Controllers\Sales;

// use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Sales\Receiptpayments;
use App\Models\Sales\Salesorder;
use App\Http\Requests\Sales\ReceiptpaymentsRequest;
use Request;
use App\Sales\Soitem;
use App\Models\Sales\Custinfo;

class ReceiptpaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($soheadId)
    {
        //
        $receiptpayments = Receiptpayments::where('sohead_id', $soheadId)->paginate(10);
        return view('sales.receiptpayments.index', compact('receiptpayments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($soheadId)
    {
        //
        $salesorder = Salesorder::findOrFail($soheadId);
//         $permissionIds = RolePermission::where('role_id', $roleId)->select('permission_id')->get();
//         $permissionList = Permission::whereNotIn('id', $permissionIds)->select('id', DB::raw('concat(name, \' - \', display_name) as name'))->lists('name', 'id');
//         if ($role != null)
//             return view('system.rolepermissions.create', compact('role', 'permissionList'));
//         else
//             return '无此角色';
        return view('sales.receiptpayments.create', compact('salesorder'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(ReceiptpaymentsRequest $request, $soheadId)
    {
        //
        $salesorder = Salesorder::findOrFail($soheadId);
        $soitems = $salesorder->soitems;
        $priceTotal = 0.0;
        foreach ($soitems as $soitem)
            $priceTotal += $soitem->price * $soitem->qty;
        
        $priceReceived = Receiptpayments::where('sohead_id', $soheadId)->sum('amount');
        
        if ($priceTotal <= $priceReceived)
            return '已完成付款';
        
        $input = Request::all();
        Receiptpayments::create($input);
        return redirect('sales/salesorders/' . $request->get('sohead_id') . '/receiptpayments');  
        

    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function storebync(ReceiptpaymentsRequest $request)
    {
        //
        $input = Request::all();

        // find customer by customer name
        // 如果不存在，则创建
        $customername = $input['CustomerName'];
        $custinfo = Custinfo::firstOrNew(['name' => $customername]);
        if (!$custinfo->number)
        {
            $custinfo->number = $customername;
            $custinfo->save();
        }

        // 如果订单不存在，则根据客户名称和工程名称添加新订单
        $projectname = $input['ProjectName'];
        $salesorder = Salesorder::firstOrNew(['descrip' => $projectname]);
        if (!$salesorder->number)
        {
            $salesorder->number = $projectname;
            $salesorder->descrip = $projectname;
            $salesorder->custinfo_id = $custinfo->id;
            $salesorder->save();
        }

        // $soitems = $salesorder->soitems;
        // $priceTotal = 0.0;
        // foreach ($soitems as $soitem)
        //     $priceTotal += $soitem->price * $soitem->qty;
        
        // $priceReceived = Receiptpayments::where('sohead_id', $soheadId)->sum('amount');
        
        // if ($priceTotal <= $priceReceived)
        //     return '已完成付款';
        
        $receiptpayment = new Receiptpayments;
        $receiptpayment->sohead_id = $salesorder->id;
        $receiptpayment->amount = $input['Amount'];
        $receiptpayment->recvdate = $input['HappenDate'];
        $receiptpayment->notes = $input['Remark'];
        $receiptpayment->save();
        // $input = Request::all();
        // Receiptpayments::create($input);

        $return = [
            'Code' => 0,
            'Description' => 'SUCCESS.'
        ];
        return json_encode($return);
        // return redirect('sales/salesorders/' . $request->get('sohead_id') . '/receiptpayments');        

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
    public function destroy($soheadId, $receivableId)
    {
        //
        Receiptpayments::destroy($receivableId);
        return redirect('sales/salesorders/' . $soheadId . '/receiptpayments');
    }
}
