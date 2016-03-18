<?php

namespace App\Http\Controllers\Inventory;

// use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Inventory\Warehouse;
use App\Http\Requests\Inventory\WarehouseRequest;
use Request;

class WarehousesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        $warehouses = Warehouse::latest('created_at')->paginate(10);
        return view('inventory.warehouses.index', compact('warehouses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        return view('inventory.warehouses.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(WarehouseRequest $request)
    {
        //
        $input = Request::all();
        Warehouse::create($input);
        return redirect('inventory/warehouses');
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
        $warehouse = Warehouse::findOrFail($id);
        return view('inventory.warehouses.edit', compact('warehouse'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(WarehouseRequest $request, $id)
    {
        //
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->update($request->all());
        return redirect('inventory/warehouses');
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
        Warehouse::destroy($id);
        return redirect('inventory/warehouses');
    }
}
