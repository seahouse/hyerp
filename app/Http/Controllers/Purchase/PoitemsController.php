<?php

namespace App\Http\Controllers\Purchase;

// use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\Purchase\PoitemRequest;
use Request;
use App\Models\Purchase\Poitem;

class PoitemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
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
    
    public function createByPoheadId($headId)
    {
        return view('purchase.poitems.create', compact('headId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(PoitemRequest $request)
    {
        //
        $input = Request::all();
        Poitem::create($input);
        return redirect('purchase/purchaseorders/' . $request->get('pohead_id') . '/detail');
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
        $poitem = Poitem::findOrFail($id);
        $headId = $poitem->pohead_id;
        return view('purchase.poitems.edit', compact('poitem', 'headId'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(PoitemRequest $request, $id)
    {
        //
        $poitem = Poitem::findOrFail($id);
        $poitem->update($request->all());
        return redirect('purchase/purchaseorders/' . $request->get('pohead_id') . '/detail');
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
        $poitem = Poitem::findOrFail($id);
        $headId = $poitem->pohead_id;
        Poitem::destroy($id);
        return redirect('purchase/purchaseorders/' . $headId . '/detail');
    }
}
