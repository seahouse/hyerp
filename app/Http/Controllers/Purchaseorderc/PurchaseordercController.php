<?php

namespace App\Http\Controllers\Purchaseorderc;

use App\Models\Purchaseorderc\Poheadc;
use App\Models\Purchaseorderc\Poitemc;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PurchaseordercController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $purchaseorders = Poheadc::latest('created_at')->paginate(10);
        return view('purchaseorderc.purchaseordercs.index', compact('purchaseorders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('purchaseorderc.purchaseordercs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $input = $request->all();
//        $input = HelperController::skipEmptyValue($input);



        $purchaseorder = Poheadc::create($input);






        return redirect('purchaseorderc/purchaseordercs');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $purchaseorder = Poheadc::findOrFail($id);
//        var_dump($report->active);
        return view('purchaseorderc.purchaseordercs.edit', compact('purchaseorder'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function detail($id)
    {
        $poitems = Poitemc::latest('created_at')->where('poheadc_id', $id)->paginate(10);
        return view('purchaseorderc.poitemcs.index', compact('poitems', 'id'));
    }
}
