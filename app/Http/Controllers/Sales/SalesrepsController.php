<?php

namespace App\Http\Controllers\Sales;

// use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Sales\Salesrep;
use App\Http\Requests\Sales\SalesrepRequest;
use Request;

class SalesrepsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        $salesreps = Salesrep::latest('created_at')->paginate(10);
        return view('sales.salesreps.index', compact('salesreps'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        return view('sales.salesreps.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(SalesrepRequest $request)
    {
        //
        $input = Request::all();
        Salesrep::create($input);
        return redirect('sales/salesreps');
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
        $salesrep = Salesrep::findOrFail($id);
        return view('sales.salesreps.edit', compact('salesrep'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(SalesrepRequest $request, $id)
    {
        //
        $salesrep = Salesrep::findOrFail($id);
        $salesrep->update($request->all());
        return redirect('sales/salesreps');
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
        Salesrep::destroy($id);
        return redirect('sales/salesreps');
    }
}
