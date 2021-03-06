<?php

namespace App\Http\Controllers\Purchase;

use App\Models\Purchase\Poheadquote_hx;
use App\Models\Purchase\Poheadtaxrateass_hxold;
use App\Models\Purchase\Purchaseorder_hx;
use App\Models\Sales\Salesorder_hxold;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Purchase\Purchaseorder;
use App\Models\Purchase\Purchaseorder_hxold;
use App\Models\Purchase\Purchaseorder_hxold_simple;
use App\Http\Requests\Purchase\PurchaseorderRequest;
use App\Models\Purchase\Poitem;
use App\Models\Purchase\Poitem_hxold;
use Carbon\Carbon;
use App\Inventory\Recvitem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PurchaseordersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        $purchaseorders = Purchaseorder::latest('created_at')->paginate(10);
        return view('purchase.purchaseorders.index', compact('purchaseorders'));
    }

    public function index_sqd()
    {
        //
        $purchaseorders = Purchaseorder_hxold::where('status', 0)->orderBy('id', 'desc')->paginate(15);
        return view('purchase.purchaseorders.index_sqd', compact('purchaseorders'));
    }

    public function index_hx(Request $request)
    {
        //
        $inputs = $request->all();
        $purchaseorders = $this->searchrequest($request)->paginate(15);
        return view('purchase.purchaseorders.index_hx', compact('purchaseorders', 'inputs'));
    }

    public function search_hx(Request $request)
    {
        $inputs = $request->all();
        $purchaseorders = $this->searchrequest($request)->paginate(15);
        return view('purchase.purchaseorders.index_hx', compact('purchaseorders', 'inputs'));
    }

    public function searchrequest($request)
    {
        $query = Purchaseorder_hxold::orderBy('id', 'desc');
        $query->where('status', 10);
        $key = $request->input('key');
        if (strlen($key) > 0) {
            $query->where('number', 'like', '%' . $key . '%');
        }
        if ($request->has('companyname') && strlen($request->input('companyname')))
            $query->where('companyname', 'like', '%' . $request->input('companyname') . '%');
        if ($request->has('supplier_name') && strlen($request->input('supplier_name')))
            $query->where('supplier_name', 'like', '%' . $request->input('supplier_name') . '%');
        if ($request->has('project_name') && strlen($request->input('project_name')))
            $query->where('sohead_descrip', 'like', '%' . $request->input('project_name') . '%');
        if ($request->has('product_name') && strlen($request->input('product_name')))
            $query->where('productname', 'like', '%' . $request->input('product_name') . '%');

        // 仅查看与自己相关的采购订单
        if (Auth::user()->cannot('purchase_purchaseorders_viewall')) {
            $userold = Auth::user()->userold;
            $query->where(function ($query) use ($userold) {
                $query->where('sohead_projectengineer_id', $userold->user_hxold_id)
                    ->orWhere('sohead_salesmanager_id', $userold->user_hxold_id)
                    ->orWhere('sohead_designer_id', $userold->user_hxold_id)
                    ->orWhere('sohead_elec_designer_id', $userold->user_hxold_id)
                    ->orWhere('applicant_id', $userold->user_hxold_id);
            });
        }

        return $query;
    }

    /**
     * Display a listing of the resource by searching order key.
     *
     * @return Response
     */
    public function getitemsbyorderkey($key, $supplierid = 0)
    {
        //
        $purchaseorders = Purchaseorder_hxold::where('vendinfo_id', $supplierid)
            ->where(function ($query) use ($key) {
                $query->where('number', 'like', '%' . $key . '%')
                    ->orWhere('descrip', 'like', '%' . $key . '%');
            })
            ->paginate(20);
        return $purchaseorders;
    }

    public function getitemsbyorderkey_simple($key, $supplierid = 0)
    {
        //
        $query = Purchaseorder_hxold_simple::orderBy('id', 'desc');
        if ($supplierid > 0)
            $query->where('vendinfo_id', $supplierid);
        if (strlen($key) > 0)
            $query->where(function ($query) use ($key) {
                $query->where('number', 'like', '%' . $key . '%')
                    ->orWhere('descrip', 'like', '%' . $key . '%');
            });
        $purchaseorders = $query->paginate(100);
        return $purchaseorders;
    }

    public function getitemsbyproductname(Request $request, $productname)
    {
        //
        $query = Purchaseorder_hxold::where('productname', $productname);
        if ($request->has('sohead_id') && $request->input('sohead_id') > 0)
            $query->where('sohead_id', $request->input('sohead_id'));
        $purchaseorders = $query->paginate(20);
        return $purchaseorders;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        return view('purchase.purchaseorders.create');
    }

    public function create_hx()
    {
        //
        return view('purchase.purchaseorders.create_hx');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(PurchaseorderRequest $request)
    {
        //
        $input = Request::all();
        Purchaseorder::create($input);
        return redirect('purchase/purchaseorders');
    }

    public function store_hx(Request $request)
    {
        //
        $this->validate($request, [
            '对应项目ID'                   => 'integer|min:1',
            '采购订单状态'                => 'required|integer|min:19',
        ]);

        $input = $request->all();
        $input['编号年份'] = Carbon::today()->year;
        $input['编号数字'] = Purchaseorder_hx::where('编号年份', Carbon::today()->year)->max('编号数字');
        $input['编号数字'] += 1;
        $input['编号商品名称'] = "spmc";
        if ($input['采购订单状态'] == 20) {
            $input['修造或工程'] = "QT";
            $input['采购订单编号'] = "QT-" . $input['编号商品名称'] . "-" . Carbon::today()->year . "-" . Carbon::today()->format("m") . "-" . $input['编号数字'];
        }

        $sohead = Salesorder_hxold::find($input['对应项目ID']);
        if (isset($sohead)) {
            $input['项目名称'] = $sohead->number . "|" . $sohead->custinfo_name . "|" . $sohead->descrip . "|" . $sohead->amount;
        }

        //        dd($input);
        Purchaseorder_hx::create($input);
        //        Purchaseorder_hxold::create($input);
        //        Purchaseorder::create($input);
        //        return redirect('purchase/purchaseorders');
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
        $purchaseorder = Purchaseorder_hxold::findOrFail($id);
        return view('purchase.purchaseorders.show', compact('purchaseorder'));
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
        $purchaseorder = Purchaseorder::findOrFail($id);
        return view('purchase.purchaseorders.edit', compact('purchaseorder'));
    }

    public function edit_hx($id)
    {
        //
        $purchaseorder = Purchaseorder_hxold_simple::findOrFail($id);
        return view('purchase.purchaseorders.edit_hx', compact('purchaseorder'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(PurchaseorderRequest $request, $id)
    {
        //
        $purchaseorder = Purchaseorder::findOrFail($id);
        $purchaseorder->update($request->all());
        return redirect('purchase/purchaseorders');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
        Purchaseorder::destroy($id);
        return redirect('purchase/purchaseorders');
    }

    public function detail($id)
    {
        $poitems = Poitem::latest('created_at')->where('pohead_id', $id)->paginate(10);
        return view('purchase.poitems.index', compact('poitems', 'id'));
    }

    public function detail_hxold($id)
    {
        $poitems = Poitem_hxold::where('pohead_id', $id)->orderBy('id')->paginate(10);
        return view('purchase.poitems_hxold.index', compact('poitems', 'id'));
    }

    public function receiving($id)
    {
        $poitems = Purchaseorder::find($id)->poitems;
        foreach ($poitems as $poitem) {
            $forQtyReceive = $poitem->qty_ordered - $poitem->qty_received;
            if ($forQtyReceive > 0.0) {
                $itemsite = $poitem->itemsite;
                if ($itemsite == null)
                    return $poitem->item->item_number . '无库存记录';

                //                 if ($itemsite->qtyonhand < $forQtyReceive)
                //                     return $soitem->item->item_number . ', 库存已不够，无法发货。';

                // create receive record
                $data = [
                    'orderitem_id' => $poitem->id,
                    'quantity' => $forQtyReceive,
                    'recvdate' => Carbon::now(),
                ];
                Recvitem::create($data);

                // update soitem qtyshipped
                $poitem->qty_received = $poitem->qty_received + $forQtyReceive;
                $poitem->save();

                // update itemsite qtyonhand
                $itemsite->qtyonhand = $itemsite->qtyonhand + $forQtyReceive;
                $itemsite->save();
            }
        }
        return redirect('purchase/purchaseorders');
    }

    // 收货单
    public function receiptorders($id)
    {
        $receiptorders = Purchaseorder_hxold::find($id)->receiptorders;
        // dd($receiptorders);
        // foreach ($poitems as $poitem)
        // {
        //     $forQtyReceive = $poitem->qty_ordered - $poitem->qty_received;
        //     if ($forQtyReceive > 0.0)
        //     {
        //         $itemsite = $poitem->itemsite;
        //         if ($itemsite == null)
        //             return $poitem->item->item_number . '无库存记录';

        //         // create receive record
        //         $data = [
        //             'orderitem_id' => $poitem->id,
        //             'quantity' => $forQtyReceive,
        //             'recvdate' => Carbon::now(),
        //         ];
        //         Recvitem::create($data);

        //         // update soitem qtyshipped
        //         $poitem->qty_received = $poitem->qty_received + $forQtyReceive;
        //         $poitem->save();

        //         // update itemsite qtyonhand
        //         $itemsite->qtyonhand = $itemsite->qtyonhand + $forQtyReceive;
        //         $itemsite->save();
        //     }
        // }
        return view('purchase.receiptorders.index', compact('receiptorders'));
    }

    /**
     * 入库信息列表
     *
     * @param [type] $id
     * @return void
     */
    public function mreceiptorders($id)
    {
        $receiptorders = Purchaseorder_hxold::find($id)->receiptorders;
        return view('purchase.receiptorders.mindex', compact('receiptorders'));
    }

    // 收货单
    public function poitems($id)
    {
        $poitems = Purchaseorder_hxold::findOrFail($id)->poitems;
        dd($poitems);

        return view('purchase.receiptorders.index', compact('receiptorders'));
    }

    // 入库单
    public function receiptorders_hx($id)
    {
        $receiptorders = Purchaseorder_hxold::find($id)->receiptorders;
        //        dd($receiptorders);
        return view('purchase.receiptorders.index_hx', compact('receiptorders'));
    }

    public function getpoheadtaxrateass_hx($id)
    {
        $receiptorders = Poheadtaxrateass_hxold::where('pohead_id', $id)->get();
        return $receiptorders;
    }

    public function arrivalticket($id)
    {
        //
        $purchaseorder = Purchaseorder_hxold::findOrFail($id);
        return view('purchase.arrivaltickets.create', compact('purchaseorder'));
    }

    /**
     * 所有到票记录（财务发票+未转化的采购发票）
     *
     * @param [type] $id
     * @return void
     */
    public function arrivaltickets($id)
    {
        // type为1时表示采购录入，还未转化为财务发票
        $first = DB::connection('sqlsrv')->table('vpurchasetickets')->where('type', 1)->where('pohead_id', $id)->select(DB::raw('number, amount, date, operator_name, remark'));
        $tickets = DB::connection('sqlsrv')->table('V到票明细')->where('所属采购订单ID', $id)->select(DB::raw("[发票号码] as no, [到票金额] as amount, [到票日期] date, [收票人] as recipient, [到票说明] as remark"))
            ->unionAll($first)->orderBy('no')->get();
        // dd($tickets);
        return view('purchase.arrivaltickets.mindex', compact('tickets'));
    }

    // 供应商报价
    public function supplierquotes($pohead_id)
    {
        //
        $poheadquotes = Poheadquote_hx::where('pohead_id', $pohead_id)->paginate(15);
        return view('purchase.supplierquotes.index', compact('poheadquotes', 'pohead_id'));
    }
}
