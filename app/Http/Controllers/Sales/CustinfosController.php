<?php

namespace App\Http\Controllers\Sales;

// use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Sales\Custinfo;
use App\Http\Requests\CustinfoRequest;
use Request;

class CustinfosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        $custinfos = Custinfo::latest('created_at')->with('contact')->paginate(10);
        return view('sales.custinfos.index', compact('custinfos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        return view('sales.custinfos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(CustinfoRequest $request)
    {
        //
        $input = Request::all();
        Custinfo::create($input);
        return redirect('sales/custinfos');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    // public static function create($customername)
    // {
    //     //
    //     $input = Request::all();
    //     Custinfo::create($input);
    //     return redirect('sales/custinfos');
    // }

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
        $custinfo = Custinfo::findOrFail($id);
        return view('sales.custinfos.edit', compact('custinfo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(CustinfoRequest $request, $id)
    {
        //
        $custinfo = Custinfo::findOrFail($id);
        $custinfo->update($request->all());
        return redirect('sales/custinfos');
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
        Custinfo::destroy($id);
        return redirect('sales/custinfos');
    }
}
