<?php

namespace App\Http\Controllers\Purchase;

use App\Models\Purchase\Prhead;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PrheadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $inputs = $request->all();
        $prheads = $this->searchrequest($request)->paginate(10);

//        $prheads = Prhead::latest('created_at')->paginate(10);
        return view('purchase.prheads.index', compact('prheads', 'inputs'));
    }

    public function search(Request $request)
    {
        $inputs = $request->all();
        $prheads = $this->searchrequest($request)->paginate(15);

        return view('purchase.prheads.index', compact('prheads', 'inputs'));
    }

    public function searchrequest($request)
    {
//        dd($request->all());
        $query = Prhead::latest('created_at');

//        if ($request->has('createdatestart') && $request->has('createdateend'))
//        {
//            $query->whereRaw("DATEDIFF(DAY, create_time, '" . $request->input('createdatestart') . "') <= 0 and DATEDIFF(DAY, create_time, '" . $request->input('createdateend') . "') >=0");
//
//        }
//
//        if ($request->has('creator_name'))
//        {
//            $query->where('creator_name', $request->input('creator_name'));
//        }

        if ($request->has('key') && strlen($request->input('key')) > 0)
        {
            $query->where('number', 'like', '%'.$request->input('key').'%');
        }

        return $query;


//        $items = $query->select('prheads.*');

//        return $items;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        Prhead::destroy($id);
        return redirect('purchase/prheads');
    }
}
