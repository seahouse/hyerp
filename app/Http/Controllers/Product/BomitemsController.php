<?php

namespace App\Http\Controllers\Product;

//use Illuminate\Http\Request;

//use App\Http\Requests;
use Request;
use App\Http\Controllers\Controller;
use App\models\Product\Bomitem;
use App\Http\Requests\BomitemRequest;

class BomitemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        return view('boms.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(BomitemRequest $request)
    {
        //
        $input = Request::all();
        $parentid = $request->input('parent_item_id');
        Bomitem::create($input);
        return redirect('product/boms/'.$parentid.'/edit');
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
        $bomitem = Bomitem::findOrFail($id);
        return view('product.bomitems.edit', compact('bomitem'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(BomitemRequest $request, $id)
    {
        //
        $parentid = Bomitem::find($id)->parent_item_id;
        $bomitem = Bomitem::findOrFail($id);
        $bomitem->update($request->all());
        return redirect('product/boms/'.$parentid.'/edit');
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
        $parentid = Bomitem::find($id)->parent_item_id;
        Bomitem::destroy($id);
        return redirect('product/boms/'.$parentid.'/edit');
    }
    
    public function createitem($parentid)
    {
//         return $parentid;
        return view('product.bomitems.createitem', compact('parentid'));
    }
}
