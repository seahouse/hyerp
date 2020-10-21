<?php

namespace App\Http\Controllers\Purchase;

use App\Models\Purchase\Prtype;
use App\Models\Purchase\Prtypeitem;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PrtypeitemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $query = Prtypeitem::latest('created_at');
        $prtype_id = 0;
        if ($request->has('prtype_id') && $request->input('prtype_id') > 0)
        {
            $prtype_id = $request->input('prtype_id');
            $query->where('prtype_id', $prtype_id);
        }
        $prtypeitems = $query->paginate(10);
        return view('purchase.prtypeitems.index', compact('prtypeitems', 'prtype_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $prtype = null;
        if ($request->has('prtype_id') && $request->input('prtype_id') > 0)
            $prtype = Prtype::find($request->input('prtype_id'));
        return view('purchase.prtypeitems.create', compact('prtype'));
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
        $inputs = $request->all();
//        dd($input->file('image_file'));
//        dd($inputs);
//        $tonnagedetailArray = json_decode($request->input('tonnagedetails_string'), true);
//        dd($tonnagedetailArray);

        $this->validate($request, [
            'prtype_id'      => 'required',
            'item_id'      => 'required',
//            'quantity'      => 'required',
//            'sohead_id'             => 'required|integer|min:1',
//            'overview'              => 'required',
//            'drawingchecker_id'     => 'required|integer|min:1',
//            'requestdeliverydate'   => 'required',
//            'drawingcount'          => 'required|integer|min:1',
//            'drawingattachments.*'  => 'required|file',
//            'images.*'                => 'required|image',
        ]);
//        $input = HelperController::skipEmptyValue($input);

        Prtypeitem::create($inputs);
        return redirect('purchase/prtypeitems?srtype_id=' . $inputs['prtype_id']);
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
        $prtypeitem = Prtypeitem::findOrFail($id);
        return view('purchase.prtypeitems.edit', compact('prtypeitem'));
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
        $prtypeitem = Prtypeitem::findOrFail($id);
        $prtypeitem->update($request->all());
        return redirect('purchase/prtypeitems?prtype_id=' . $prtypeitem->prtype_id);
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
        $prtypeitem = Prtypeitem::findOrFail($id);
        $prtype_id = $prtypeitem->prtype_id;
        Prtypeitem::destroy($id);
        return redirect('purchase/prtypeitems?prtype_id=' . $prtype_id);
    }
}
