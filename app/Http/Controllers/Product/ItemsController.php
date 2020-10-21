<?php

namespace App\Http\Controllers\Product;

use App\Models\Product\Bom_hxold;
use App\Models\Product\Item;
use App\Models\Product\Pdm\Cfdict;
use App\Models\Product\Pdm\Cffdr;
use App\Models\Product\Pdm\Cffdrref;
use App\Models\Product\Pdm\Cfobjkind;
use App\Models\Product\Pdmitem;
use App\Models\Purchase\Prhead;
use App\Models\Purchase\Purchaseorder_hxold;
use Illuminate\Http\Request;
//use Request;
use App\Http\Requests\ItemRequest;
use DB, Log;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Product\Itemclass;
use App\Models\Product\Itemtype;
use App\Models\Inventory\Warehouse;
use App\Models\Inventory\Itemsite;
use App\Models\Product\Itemp_hxold;
use App\Models\Product\Itemp_hxold_t;
use App\Models\Product\Itemp_hxold2;
use App\Models\Inventory\Receiptitem_hxold;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
//         $items = Item::latest('created_at')->get();
        $items = Item::latest('created_at')->paginate(10);
//         $itemclass = Item::find($id)->itemclass;
//         $items = Item::paginate(5);
        return view('product.items.index', compact('items'));
    }

    public function indexp_hxold()
    {
        //
        $request = request();
        $key = $request->input('key', '');
        $inputs = $request->all();
        if (null !== request('key'))
            $items = $this->searchrequest($request);
        else
            $items = Itemp_hxold::latest('add_time')->paginate(10);

        if (null !== request('key'))
        {
            return view('product.items.indexp_hxold', compact('items', 'key', 'inputs'));
        }
        else
        {
            return view('product.items.indexp_hxold', compact('items'));
        }

//        return view('product.items.indexp_hxold', compact('items'));
    }
    
    /**
     * 
     */
    public function mindex()
    {
        $items = Item::latest('created_at')->paginate(10);
        return $items;
    }
    
    public function mmindex()
    {
        $items = Item::latest('created_at')->paginate(10);
        return view('product.items.mindex');
    }
    
    public function search(Request $request)
    {
        $key = $request->input('key');
        if ($key == '')
            return redirect('items');
        
        $items = Item::latest('created_at')->where('item_number', 'like', '%' . $key . '%')->orWhere('item_name', 'like', '%' . $key . '%')->paginate(10);
        return view('product.items.index', compact('items'));
    }

    public function itemp_hxold_search(Request $request)
    {
//        $key = $request->input('key');
//        $numberstatus = $request->input('numberstatus');
//        if ($key == '')
//            return redirect('product/indexp_hxold');

//        $query = Itemp_hxold::latest('add_time');
//        if (strlen($key) > 0)
//        {
//            $query->where('goods_no', 'like', '%' . $key . '%');
//        }
//
//        if (strlen($numberstatus) > 0)
//        {
//            if ($numberstatus === "已设置")
//                $query->where('goods_no2', '!=', '');
//            elseif ($numberstatus === "未设置")
//                $query->where('goods_no2', '=', '');
//        }

//        $items = $query->select('*')->paginate(10);
//        $items = Itemp_hxold::latest('add_time')->where('goods_no', 'like', '%' . $key . '%')->paginate(10);

        $key = $request->input('key');
        $inputs = $request->all();
        $items = $this->searchrequest($request);
        return view('product.items.indexp_hxold', compact('items', 'key', 'inputs'));
//        return view('product.items.indexp_hxold', compact('items'));
    }

    private function searchrequest($request)
    {
        $key = $request->input('key');
        $numberstatus = $request->input('numberstatus');
//        if ($key == '')
//            return redirect('product/indexp_hxold');

        $query = Itemp_hxold::latest('add_time');
        if (strlen($key) > 0)
        {
            $query->where('goods_no', 'like', '%' . $key . '%');
        }

        if (strlen($numberstatus) > 0)
        {
            if ($numberstatus === "已设置")
                $query->where('goods_no2', '!=', '');
            elseif ($numberstatus === "未设置")
                $query->where('goods_no2', '=', '');
        }

        $items = $query->select('*')->paginate(10);
        return $items;
    }

    public function getitemsbykey($key)
    {
        //
        $items = Itemp_hxold::where('goods_id', -1)->paginate(50);
        if ($key <> "")
            $items = Itemp_hxold::where(function ($query) use ($key) {
                $query->where('goods_name', 'like', '%' . $key . '%')
                    ->orWhere('goods_old_name', 'like', '%' . $key . '%');
            })
                ->whereNull('end_date')
                ->paginate(500);

        return $items;
    }

    // 获取采购申请单中的物料
    public function getitemsbyprhead($prhead_id)
    {
        //
        $prhead = Prhead::findOrFail($prhead_id);
        $items = $prhead->pritems->pluck('item');

        return $items;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
//         $itemclasslist = Itemclass::lists('name', 'id');
//         $itemtypeList = Itemtype::lists('name', 'id');
//         return view('items.create', array(
//             'itemclasslist' => $itemclasslist,
//             'itemtypeList' => $itemtypeList
//         ));
        return view('product.items.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(ItemRequest $request)
    {
        //
        $input = $request->all();
        $item = Item::create($input);

        $warehouse = Warehouse::first();
        if ($warehouse != null)
        {
            $data = [
                'item_id' => $item->id,
                'warehouse_id' => $warehouse->id,
                'qtyonhand' => 0.0,
            ];
            Itemsite::create($data);
        }

        
        return redirect('/product/items');
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
//         $item = Item::findOrFail($id);
//         $itemclass = Item::find($id)->itemclass;
//         $itemtype = Item::find($id)->itemtype;
//         return view('items.show', compact('item', 'itemclass', 'itemtype'));

        return $this->edit($id);
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
        $item = Item::findOrFail($id);
//         $itemclasslist = Itemclass::lists('name', 'id');
//         $itemtypeList = Itemtype::lists('name', 'id');
//         return view('items.edit', compact('item', 'itemclasslist', 'itemtypeList'));
        return view('product.items.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'item_number' => 'required',
            'itemclass_id' => 'required',
            'itemtype_id' => 'required'
        ]);
        
        $item = Item::findOrFail($id);
        $item->update($request->all());
        
        $warehouse = Warehouse::first();
        $itemsite = $item->itemsite;
        if ($warehouse != null && $itemsite == null)
        {
            $data = [
                'item_id' => $item->id,
                'warehouse_id' => $warehouse->id,
                'qtyonhand' => 0.0,
            ];
            Itemsite::create($data);
        }
        
        return redirect('product/items');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        // 通过删除itemsite，同时删除了item
        $item = Item::findOrFail($id);
        $itemsite = $item->itemsite;
        if ($itemsite != null)
            Itemsite::destroy($itemsite->id);
        
        Item::destroy($id);
        return redirect('product/items');
    }

    // 获取入库信息
    public function receiptitems($id)
    {
        $item_number = Itemp_hxold::where('goods_id', $id)->first()->goods_no;
        $receiptitems = Receiptitem_hxold::where('item_number', $item_number)->paginate(10);
        // dd($receiptitems);
        return view('inventory.receiptitems.index', compact('receiptitems'));
    }

    public function sethxold2($id)
    {
        $itemp = Itemp_hxold::where('goods_id', $id)->firstOrFail();
        $items2 = Itemp_hxold2::where('goods_name', $itemp->goods_name)->paginate(20);
        
        return view('product.items.sethxold2', compact('itemp', 'items2'));
    }

    public function msethxold2($id)
    {
        $itemp = Itemp_hxold::where('goods_id', $id)->firstOrFail();
        $items2 = Itemp_hxold2::where('goods_name', $itemp->goods_name)->paginate(20);
        
        return view('product.items.msethxold2', compact('itemp', 'items2'));
    }

    public function sethxold2update($id, $id2)
    {
        $itemp = Itemp_hxold::where('goods_id', $id)->firstOrFail();
        $itemp2 = Itemp_hxold2::where('goods_id', $id2)->firstOrFail();
        $items2 = Itemp_hxold2::where('goods_name', $itemp->goods_name)->paginate(20);

        $pdo = DB::connection('sqlsrv')->getPdo();
        $stmt = $pdo->prepare("EXEC pSetGoodsNo2 ?,?");
        $goods_no2 = $itemp2->goods_no;
        $stmt->bindParam(1, $id);
        $stmt->bindParam(2, $goods_no2);
        $stmt->execute();
        // $pdo->select('exec pSetGoodsNo2 ?,?', [$id, $itemp2->goods_no]);
        
        // DB::connection('sqlsrv')->select('exec pSetGoodsNo2 ?,?', [$id, $itemp2->goods_no]);
        
        return redirect('product/indexp_hxold/' . $itemp->goods_id . '/sethxold2');
        // return view('product.items.sethxold2', compact('itemp', 'items2'));
    }

    public function resetitempnumber(Request $request)
    {
//        $items = Itemp_hxold::leftJoin('vgoods2', function ($join) {
//            $join->on('vgoods2.goods_name', '=', 'vgoods.goods_name')->orOn('vgoods2.goods_spec', '=', 'vgoods.goods_spec');
//        } )->get();

//        DB::connection('foo')->update()
//        return 0;

        $from = $request->input('from', 0);
//        $from = 0;
        $items = Itemp_hxold_t::orderBy('goods_id')->skip($from)->take(100)->get();
        $count = 0;
        foreach ($items as $item)
        {
            $items2 = Itemp_hxold2::where('goods_name', $item->goods_name)->where('goods_spec', $item->goods_spec)->get();
            if ($items2->count() === 1)
            {
                $item2 = $items2->first();
                if ($item2->goods_no !== '')
                {
                    Itemp_hxold_t::where('goods_id', $item->goods_id)->update(['goods_no2' => $item2->goods_no]);
//                    dd('change goods(goods_id:' . $item->goods_id . ') goods_no2 to ' . $items2->first()->goods_no);
                }
            }
            $count++;
        }
//        dd($items->count());
        $data = ['count' => $count, 'sum' => Itemp_hxold_t::count()];
        return json_encode($data);
//        return Itemp_hxold_t::count();
//        return $count;
//        return redirect('product/indexp_hxold/');

    }

    public function topdm($id)
    {
        $itemp = Itemp_hxold::where('goods_id', $id)->firstOrFail();
//        $pdmitem = Pdmitem::where('itemid', 4573)->firstOrFail();
        $pdmitem = new Pdmitem();
        $itemid = Pdmitem::max('itemid');
        $itemid += 1;
        $pdmitem->itemid = $itemid;
        $pdmitem->revlabel = 'A.1';
        $pdmitem->itemcode = $itemp->goods_no;
        $pdmitem->itemname = $itemp->goods_name;
        $pdmitem->spec = $itemp->goods_spec;
        $pdmitem->itemkindid = 1035;
        $pdmitem->save();

        $cffdrref = new Cffdrref();
        $cffdrref->objid=887;
        $cffdrref->objrev=0;
        $cffdrref->refobjid=$itemid;
        $cffdrref->refobjrev=1;
        $cffdrref->refobjtypeid=21;
        $cffdrref->reftype=1;
        $cffdrref->seq=1;
        $cffdrref->fdrreftag=0;
        $cffdrref->save();
        dd($pdmitem);

        return view('product.items.sethxold2', compact('itemp', 'items2'));
    }

    public function itemstopdm()
    {
        $cffdr = Cffdr::where('fdrname', 'ERP导入')->firstOrFail();
        if (isset($cffdr))
        {
            Log::info('itemstopdm 1');
            $items = Itemp_hxold::where('goods_no', '<>', '')->take(10)->get();
            foreach ($items as $item)
            {
                Log::info('itemstopdm 2');
                Log::info($item->type_name);
                Log::info($item->goods_no);
                if (empty($item->type_name) || empty($item->goods_no)) continue;

                $subcffdr = Cffdr::where('fdrname', $item->type_name)->first();
                Log::info('itemstopdm 3');
                if (!isset($subcffdr))
                {
                    Log::info('itemstopdm 4');
                    $subcffdr = new Cffdr();
                    $fdrid = Cffdr::max('fdrid');
                    $fdrid += 1;
                    $subcffdr->fdrid = $fdrid;
                    $subcffdr->rev = 1;
                    $subcffdr->fdrname = $item->type_name;
                    $subcffdr->stat = 1;
                    $subcffdr->actived = 2;
                    $subcffdr->type = 3;
                    $subcffdr->hide = 1;
                    $subcffdr->creator = 'admin';
                    $subcffdr->updator = 'admin';
                    $subcffdr->idpathdb = '-99\\' . $cffdr->fdrid . '\\' . $fdrid . '\\';
                    $subcffdr->typepathdb = '-1\\2\\2\\';
                    $subcffdr->namepathdb = '标准物料库\\ERP导入\\' . $item->type_name . '\\';
                    $subcffdr->fdrkindid = 60;
                    $subcffdr->spobjid = $cffdr->fdrid;
                    $subcffdr->spobjtypeid = 2;
                    $subcffdr->revlabel = 'A.1';
                    $subcffdr->save();

                    $cffdrref = new Cffdrref();
                    $cffdrref->objid = $cffdr->fdrid;
                    $cffdrref->objrev = 1;
                    $cffdrref->refobjid = $fdrid;
                    $cffdrref->refobjrev = 1;
                    $cffdrref->refobjtypeid = 2;
                    $cffdrref->reftype = 1;
                    $cffdrref_seq = Cffdrref::where('objid', $cffdr->fdrid)->max('seq');
                    if (isset($cffdrref_seq))
                        $cffdrref_seq += 1;
                    else
                        $cffdrref_seq = 1;
                    $cffdrref->seq = $cffdrref_seq;
                    $cffdrref->fdrreftag=0;
                    $cffdrref->save();

                    dd($cffdrref);
                }
//                dd($item->type_name);

                $pdmitem = Pdmitem::where('itemcode', $item->goods_no)->first();
                if (!isset($pdmitem))
                {
                    $pdmitem = new Pdmitem();
                    $itemid = Pdmitem::max('itemid');
                    $itemid += 1;
                    $pdmitem->itemid = $itemid;
                    $pdmitem->rev = 1;
                    $pdmitem->revlabel = 'A.1';
                    $pdmitem->itemcode = $item->goods_no;
                    $pdmitem->itemname = $item->goods_name;
                    $pdmitem->spec = $item->goods_spec;
                    $pdmitem->itemtype = 4;             // 含义参考: select * from cfdict where pid=9 中的 dictvalue 字段, 原材料
                    $unitname = $item->goods_unit_name;
                    if (!empty($unitname))
                    {
                        $cfdict = Cfdict::where('pid', 19)->where('dictname', $unitname)->first();
                        if (isset($cfdict))
                        {
                            $pdmitem->weightunit = $cfdict->dictvalue;
                        }
                    }
                    $pdmitem->stat = 1;             // select * from cfdict where pid=51
                    $pdmitem->actived = 2;
                    $pdmitem->creator = 'hyerp';
                    $pdmitem->updator = 'hyerp';
                    $cfobjkind = Cfobjkind::where('objkindname', '外购件')->first();
                    if (isset($cfobjkind))
                        $pdmitem->itemkindid = $cfobjkind->objkindid;
                    $pdmitem->idpathdb = '-99\\' . $cffdr->fdrid . '\\' . $subcffdr->fdrid . '\\' . $itemid . '\\';
                    $pdmitem->typepathdb = '-1\\2\\2\\21\\';
                    $pdmitem->namepathdb = '标准物料库\\ERP导入\\' . $item->type_name . '\\' . $item->goods_name . '\\';
                    $pdmitem->citemid = $itemid;
                    $pdmitem->save();

                    $cffdrref = new Cffdrref();
                    $cffdrref->objid = $subcffdr->fdrid;
                    $cffdrref->objrev = 0;
                    $cffdrref->refobjid = $itemid;
                    $cffdrref->refobjrev = 1;
                    $cffdrref->refobjtypeid = 21;
                    $cffdrref->reftype = 1;
                    $cffdrref_seq = Cffdrref::where('objid', $subcffdr->fdrid)->max('seq');
                    if (isset($cffdrref_seq))
                        $cffdrref_seq += 1;
                    else
                        $cffdrref_seq = 1;
                    $cffdrref->seq = $cffdrref_seq;
                    $cffdrref->fdrreftag=0;
                    $cffdrref->save();

                    dd($cffdrref);
                }
            }
        }


        dd('aaa');

        return view('product.items.sethxold2', compact('itemp', 'items2'));
    }

    public function bomstopdm()
    {
        $cffdr = Cffdr::where('fdrname', 'ERP系列')->firstOrFail();
        if (isset($cffdr))
        {
            $bom_pids = Bom_hxold::select('pid')->distinct()->take(10)->get();
            dd($bom_pids);
            foreach ($bom_pids as $bom_pid)
            {
//                $bomitem = Itemp_hxold::where('goods_id', '<>', '')->take(10)->get();
            }
            foreach ($items as $item)
            {
                Log::info('itemstopdm 2');
                Log::info($item->type_name);
                Log::info($item->goods_no);
                if (empty($item->type_name) || empty($item->goods_no)) continue;

                $subcffdr = Cffdr::where('fdrname', $item->type_name)->first();
                Log::info('itemstopdm 3');
                if (!isset($subcffdr))
                {
                    Log::info('itemstopdm 4');
                    $subcffdr = new Cffdr();
                    $fdrid = Cffdr::max('fdrid');
                    $fdrid += 1;
                    $subcffdr->fdrid = $fdrid;
                    $subcffdr->rev = 1;
                    $subcffdr->fdrname = $item->type_name;
                    $subcffdr->stat = 1;
                    $subcffdr->actived = 2;
                    $subcffdr->type = 3;
                    $subcffdr->hide = 1;
                    $subcffdr->creator = 'admin';
                    $subcffdr->updator = 'admin';
                    $subcffdr->idpathdb = '-99\\' . $cffdr->fdrid . '\\' . $fdrid . '\\';
                    $subcffdr->typepathdb = '-1\\2\\2\\';
                    $subcffdr->namepathdb = '标准物料库\\ERP导入\\' . $item->type_name . '\\';
                    $subcffdr->fdrkindid = 60;
                    $subcffdr->spobjid = $cffdr->fdrid;
                    $subcffdr->spobjtypeid = 2;
                    $subcffdr->revlabel = 'A.1';
                    $subcffdr->save();

                    $cffdrref = new Cffdrref();
                    $cffdrref->objid = $cffdr->fdrid;
                    $cffdrref->objrev = 1;
                    $cffdrref->refobjid = $fdrid;
                    $cffdrref->refobjrev = 1;
                    $cffdrref->refobjtypeid = 2;
                    $cffdrref->reftype = 1;
                    $cffdrref_seq = Cffdrref::where('objid', $cffdr->fdrid)->max('seq');
                    if (isset($cffdrref_seq))
                        $cffdrref_seq += 1;
                    else
                        $cffdrref_seq = 1;
                    $cffdrref->seq = $cffdrref_seq;
                    $cffdrref->fdrreftag=0;
                    $cffdrref->save();

                    dd($cffdrref);
                }
//                dd($item->type_name);

                $pdmitem = Pdmitem::where('itemcode', $item->goods_no)->first();
                if (!isset($pdmitem))
                {
                    $pdmitem = new Pdmitem();
                    $itemid = Pdmitem::max('itemid');
                    $itemid += 1;
                    $pdmitem->itemid = $itemid;
                    $pdmitem->rev = 1;
                    $pdmitem->revlabel = 'A.1';
                    $pdmitem->itemcode = $item->goods_no;
                    $pdmitem->itemname = $item->goods_name;
                    $pdmitem->spec = $item->goods_spec;
                    $pdmitem->itemtype = 4;             // 含义参考: select * from cfdict where pid=9 中的 dictvalue 字段, 原材料
                    $unitname = $item->goods_unit_name;
                    if (!empty($unitname))
                    {
                        $cfdict = Cfdict::where('pid', 19)->where('dictname', $unitname)->first();
                        if (isset($cfdict))
                        {
                            $pdmitem->weightunit = $cfdict->dictvalue;
                        }
                    }
                    $pdmitem->stat = 1;             // select * from cfdict where pid=51
                    $pdmitem->actived = 2;
                    $pdmitem->creator = 'hyerp';
                    $pdmitem->updator = 'hyerp';
                    $cfobjkind = Cfobjkind::where('objkindname', '外购件')->first();
                    if (isset($cfobjkind))
                        $pdmitem->itemkindid = $cfobjkind->objkindid;
                    $pdmitem->idpathdb = '-99\\' . $cffdr->fdrid . '\\' . $subcffdr->fdrid . '\\' . $itemid . '\\';
                    $pdmitem->typepathdb = '-1\\2\\2\\21\\';
                    $pdmitem->namepathdb = '标准物料库\\ERP导入\\' . $item->type_name . '\\' . $item->goods_name . '\\';
                    $pdmitem->citemid = $itemid;
                    $pdmitem->save();

                    $cffdrref = new Cffdrref();
                    $cffdrref->objid = $subcffdr->fdrid;
                    $cffdrref->objrev = 0;
                    $cffdrref->refobjid = $itemid;
                    $cffdrref->refobjrev = 1;
                    $cffdrref->refobjtypeid = 21;
                    $cffdrref->reftype = 1;
                    $cffdrref_seq = Cffdrref::where('objid', $subcffdr->fdrid)->max('seq');
                    if (isset($cffdrref_seq))
                        $cffdrref_seq += 1;
                    else
                        $cffdrref_seq = 1;
                    $cffdrref->seq = $cffdrref_seq;
                    $cffdrref->fdrreftag=0;
                    $cffdrref->save();

                    dd($cffdrref);
                }
            }
        }
        else
            dd('请在产品工作区建立ERP系列文件夹');


        dd('aaa');

        return view('product.items.sethxold2', compact('itemp', 'items2'));
    }
}
