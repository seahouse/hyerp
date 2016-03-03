<?php

namespace App\Http\Controllers\Addr;

// use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Addr;
use App\Http\Requests\Addr\AddrRequest;
use Request;

class AddrsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        $addrs = Addr::latest('created_at')->with('province')->with('city')->paginate(10);
        return view('addr.addrs.index', compact('addrs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        return view('addr.addrs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(AddrRequest $request)
    {
        //
        $input = Request::all();
        Addr::create($input);
        return redirect('addr/addrs');
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
        $addr = Addr::findOrFail($id);
        return view('addr.addrs.edit', compact('addr'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(AddrRequest $request, $id)
    {
        //
        $addr = Addr::findOrFail($id);
        $addr->update($request->all());
        return redirect('addr/addrs');
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
        Addr::destroy($id);
        return redirect('addr/addrs');
    }
}
