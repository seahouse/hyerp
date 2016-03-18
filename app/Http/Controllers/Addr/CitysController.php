<?php

namespace App\Http\Controllers\Addr;

// use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Crm\City;
use App\Http\Requests\Addr\CityRequest;
use Request;

class CitysController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        $citys = City::latest('created_at')->with('province')->paginate(10);
        return view('addr.citys.index', compact('citys'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        return view('addr.citys.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(CityRequest $request)
    {
        //
        $input = Request::all();
        City::create($input);
        return redirect('addr/citys');
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
        $city = City::findOrFail($id);
        return view('addr.citys.edit', compact('city'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(CityRequest $request, $id)
    {
        //
        $city = City::findOrFail($id);
        $city->update($request->all());
        return redirect('addr/citys');
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
        City::destroy($id);
        return redirect('addr/citys');
    }
}
