<?php

namespace App\Http\Controllers\purchase;

// use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Purchase\Vendtype;
use App\Http\Requests\purchase\VendtypeRequest;
use Request;

class VendtypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        $vendtypes = Vendtype::latest('created_at')->paginate(10);
        return view('purchase.vendtypes.index', compact('vendtypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        return view('purchase.vendtypes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(VendtypeRequest $request)
    {
        //
        $input = Request::all();
        Vendtype::create($input);
        return redirect('purchase/vendtypes');
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
        $vendtype = Vendtype::findOrFail($id);
        return view('purchase.vendtypes.edit', compact('vendtype'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(VendtypeRequest $request, $id)
    {
        //
        $vendtype = Vendtype::findOrFail($id);
        $vendtype->update($request->all());
        return redirect('purchase/vendtypes');
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
        Vendtype::destroy($id);
        return redirect('purchase/vendtypes');
    }
}
