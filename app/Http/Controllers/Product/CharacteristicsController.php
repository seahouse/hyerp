<?php

namespace App\Http\Controllers\Product;

// use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Product\Characteristic;
use App\Http\Requests\Product\CharacteristicRequest;
use Request;

class CharacteristicsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        $characteristics = Characteristic::latest('created_at')->paginate(10);        
        return view('product.characteristics.index', compact('characteristics'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        return view('product.characteristics.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(CharacteristicRequest $request)
    {
        //
        $input = Request::all();
        Characteristic::create($input);
        return redirect('product/characteristics');
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
        $characteristic = Characteristic::findOrFail($id);
//         return $characteristic;
        return view('product.characteristics.edit', compact('characteristic'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(CharacteristicRequest $request, $id)
    {
        //
        $characteristic = Characteristic::findOrFail($id);
        $characteristic->update($request->all());
        return redirect('product/characteristics');
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
        Characteristic::destroy($id);
        return redirect('product/characteristics');
    }
}
