<?php

namespace App\Http\Controllers\Sales;

// use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Sales\Soitem;
use App\Http\Requests\Sales\SoitemRequest;
use Request;

class SoitemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        $soitems = Soitem::latest('created_at')->paginate(10);
        return view('sales.soitems.index', compact('soitems'));
    }
    
    public function listBySoheadId($headId)
    {
        $soitems = Soitem::latest('created_at')->where('sohead_id', $headId)->paginate(10);
        return view('sales.soitems.index', compact('soitems', 'headId'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        return view('sales.soitems.create');
    }
    
    public function createBySoheadId($headId)
    {
        //
        return view('sales.soitems.create', compact('headId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(SoitemRequest $request)
    {
        //
        $input = Request::all();
        Soitem::create($input);
        return redirect('sales/soitems/' . $request->get('sohead_id') . '/list');
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
        $soitem = Soitem::findOrFail($id);
        $headId = $soitem->head_id;
        return view('sales.soitems.edit', compact('soitem', 'headId'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(SoitemRequest $request, $id)
    {
        //
        $soitem = Soitem::findOrFail($id);
        $soitem->update($request->all());
        return redirect('sales/soitems/' . $request->get('sohead_id') . '/list');
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
        Soitem::destroy($id);
        return redirect('sales/soitems');
    }
}
