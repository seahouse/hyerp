<?php

namespace App\Http\Controllers\Sales;

// use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Sales\Salesorder;
use App\Http\Requests\Sales\SalesorderRequest;
use Httpful\Response;
use Request;
use App\Inventory\Itemsite;
use Carbon\Carbon;
use App\Inventory\Shipitem;
use App\Sales\Soitem;
use App\Models\Sales\Salesorder_hxold;
use App\Models\Sales\Custinfo_hxold;

class SalesOrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        $salesorders = Salesorder::latest('created_at')->paginate(10);
        return view('sales.salesorders.index', compact('salesorders'));
    }
    
    public function mindex()
    {
        $salesorders = Salesorder::latest('created_at')->paginate(10);
        return $salesorders;
    }
    
    public function search(\Illuminate\Http\Request $request)
    {
        $key = $request->input('key');
        if ($key == '')
            return redirect('sales/salesorders');
    
        $salesorders = Salesorder::latest('created_at')->where('number', 'like', '%' . $key . '%')->paginate(10);
//         $salesorders = Salesorder::latest('created_at')->where('number', 'like', '%' . $key . '%')->orWhere('item_name', 'like', '%' . $key . '%')->paginate(10);
        return view('sales.salesorders.index', compact('salesorders'));
    }

    public function getitemsbykey($key, $customerid=0)
    {
        // $salesorders = Salesorder::latest('created_at')->where('number', 'like', '%' . $key . '%')
        //     ->orWhere('descrip', 'like', '%'.$key.'%')->paginate(20);
        $query = Salesorder_hxold::orderBy('id', 'desc');
        if ($customerid > 0)
        {
            $query->where('custinfo_id', $customerid);

        }
        $query->where(function ($query) use ($key) {
            $query->where('number', 'like', '%'.$key.'%')
                ->orWhere('descrip', 'like', '%'.$key.'%');
        });
        $salesorders = $query->paginate(20);

//        return $salesorders;
        return response($salesorders)
            ->header('Access-Control-Allow-Origin', 'http://www.huaxing-east.cn:2016');
    }

    /**
     * Return item value by id.
     *
     * @return Response
     */
    public function getitembyid($id)
    {
        // $salesorders = Salesorder::latest('created_at')->where('number', 'like', '%' . $key . '%')
        //     ->orWhere('descrip', 'like', '%'.$key.'%')->paginate(20);
        $salesorder = Salesorder_hxold::findOrFail($id);       
        return $salesorder;
    }

    public function getsohx()
    {
        $salesorders = Salesorder_hxold::paginate(15);
        // dd($salesorders);
        return view('sales.salesorders.indexhx', compact('salesorders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        return view('sales.salesorders.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(SalesorderRequest $request)
    {
        //
        $input = Request::all();
        Salesorder::create($input);
        return redirect('sales/salesorders');
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
        $salesorder = Salesorder::findOrFail($id);
        return view('sales.salesorders.edit', compact('salesorder'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(SalesorderRequest $request, $id)
    {
        //
        $salesorder = Salesorder::findOrFail($id);
        $salesorder->update($request->all());
        return redirect('sales/salesorders');
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
        Salesorder::destroy($id);
        return redirect('sales/salesorders');
    }
    
    public function ship($id)
    {
        $soitems = Salesorder::find($id)->soitems;
        foreach ($soitems as $soitem)
        {
            $forQtyshipped = $soitem->qty - $soitem->qtyshipped;
            if ($forQtyshipped > 0.0)
            {
                $itemsite = $soitem->itemsite;
                if ($itemsite == null)
                    return $soitem->item->item_number . '无库存记录';
                
                if ($itemsite->qtyonhand < $forQtyshipped)
                    return $soitem->item->item_number . ', 库存已不够，无法发货。';
                
                // create shipto record
                $data = [
                    'orderitem_id' => $soitem->id,
                    'quantity' => $forQtyshipped,
                    'shipdate' => Carbon::now(),
                ];
                Shipitem::create($data);
                
                // update soitem qtyshipped
                $soitem->qtyshipped = $soitem->qtyshipped + $forQtyshipped;
                $soitem->save();
                
                // update itemsite qtyonhand
                $itemsite->qtyonhand = $itemsite->qtyonhand - $forQtyshipped;
                $itemsite->save();
            }
        }
        return redirect('sales/salesorders');
    }

    public function mstatistics($id)
    {
        $sohead = Salesorder_hxold::find($id);

        return view('sales.salesorders.mstatistics', compact('sohead'));
    }
}
