<?php

namespace App\Http\Controllers\purchase;

// use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Purchase\Vendinfo;
use App\Models\Purchase\Vendinfo_hxold;
use App\Http\Requests\Purchase\VendinfoRequest;
use Request;

class VendinfosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        $vendinfos = Vendinfo::latest('created_at')->paginate(10);
        return view('purchase.vendinfos.index', compact('vendinfos'));
    }

    /**
     * Display a listing of the resource by search.
     *
     * @return Response
     */
    public function getitemsbykey($key)
    {
        // $salesorders = Salesorder::latest('created_at')->where('number', 'like', '%' . $key . '%')
        //     ->orWhere('descrip', 'like', '%'.$key.'%')->paginate(20);
        // $vendinfos = Vendinfo::where('name', 'like', '%' . $key . '%')->paginate(20);
        $vendinfos = Vendinfo_hxold::where('name', 'like', '%' . $key . '%')->paginate(20);
        return $vendinfos;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        return view('purchase.vendinfos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(VendinfoRequest $request)
    {
        //
        $input = Request::all();
        Vendinfo::create($input);
        return redirect('purchase/vendinfos');
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
        $vendinfo = Vendinfo::findOrFail($id);
        return view('purchase.vendinfos.edit', compact('vendinfo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(VendinfoRequest $request, $id)
    {
        //
        $vendinfo = Vendinfo::findOrFail($id);
        $vendinfo->update($request->all());
        return redirect('purchase/vendinfos');
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
        Vendinfo::destroy($id);
        return redirect('purchase/vendinfos');
    }
}
