<?php

namespace App\Http\Controllers\Product;

use App\Models\Product\Item;
use Illuminate\Http\Request;
//use Request;
use App\Http\Requests\ItemRequest;
use DB;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Product\Itemclass;
use App\Models\Product\Itemtype;
use App\Models\Inventory\Warehouse;
use App\Models\Inventory\Itemsite;
use App\Models\Product\Itemp_hxold;
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
        $items = Itemp_hxold::latest('add_time')->paginate(10);
        return view('product.items.indexp_hxold', compact('items'));
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
        $key = $request->input('key');
        if ($key == '')
            return redirect('product/indexp_hxold');
        
        $items = Itemp_hxold::latest('add_time')->where('goods_no', 'like', '%' . $key . '%')->paginate(10);
        return view('product.items.indexp_hxold', compact('items'));
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
}
