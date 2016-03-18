<?php

namespace App\Http\Controllers\Addr;

// use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Crm\Province;
use App\Http\Requests\Addr\ProvinceRequest;
use Request;
use App\City;

class ProvincesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        $provinces = Province::latest('created_at')->paginate(10);
        return view('addr.provinces.index', compact('provinces'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        return view('addr.provinces.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(ProvinceRequest $request)
    {
        //
        $input = Request::all();
        Province::create($input);
        return redirect('addr/provinces');
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
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
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
        Province::destroy($id);
        return redirect('addr/provinces');
    }
    
    public function getIndex()
    {
//         return City::all();
        $input = Request::get('option');
        $province = Province::find($input);
        $cities = $province->cities();
        return $cities->get(['id', 'name']);
    }
}
