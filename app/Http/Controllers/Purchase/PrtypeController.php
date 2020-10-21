<?php

namespace App\Http\Controllers\Purchase;

use App\Models\Purchase\Prhead;
use App\Models\Purchase\Prtype;
use App\Models\Purchase\Purchaseorder_hxold;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PrtypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $query = Prtype::latest('created_at');
        $prhead_id = 0;
        if ($request->has('prhead_id') && $request->input('prhead_id') > 0)
        {
            $prhead_id = $request->input('prhead_id');
            $query->where('prhead_id', $prhead_id);
        }
        $prtypes = $query->paginate(10);
        return view('purchase.prtypes.index', compact('prtypes', 'prhead_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $prhead = null;
        if ($request->has('prhead_id') && $request->input('prhead_id') > 0)
            $prhead = Prhead::find($request->input('prhead_id'));
        return view('purchase.prtypes.create', compact('prhead'));
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
            'prhead_id'      => 'required',
            'supplier_id'      => 'required',
//            'materialsupplier'      => 'required',
//            'sohead_id'             => 'required|integer|min:1',
//            'overview'              => 'required',
//            'drawingchecker_id'     => 'required|integer|min:1',
//            'requestdeliverydate'   => 'required',
//            'drawingcount'          => 'required|integer|min:1',
//            'drawingattachments.*'  => 'required|file',
//            'images.*'                => 'required|image',
        ]);
//        $input = HelperController::skipEmptyValue($input);

        Prtype::create($inputs);
        return redirect('purchase/prtypes?srhead_id=' . $inputs['prhead_id']);
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
        $prtype = Prtype::findOrFail($id);
        return view('purchase.prtypes.edit', compact('prtype'));
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
        $prtype = Prtype::findOrFail($id);
        $prtype->update($request->all());
        return redirect('purchase/prtypes?prhead_id=' . $prtype->prhead_id);
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
        $prtype = Prtype::findOrFail($id);
        $prhead_id = $prtype->prhead_id;
        Prtype::destroy($id);
        return redirect('purchase/prtypes?prhead_id=' . $prhead_id);
    }
}
