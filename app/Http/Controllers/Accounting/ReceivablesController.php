<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Accounting\Receivable;
use App\Custinfo;
use DB;
use App\Sales\Soitem;

class ReceivablesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
//         $soitems = Soitem::latest('created_at')->get();
//         return DB::table('soitems')->selectRaw('SUM(qty * price) as total')->pluck('total');
        
        $receivables = Custinfo::latest('created_at')->paginate(10);
//         $receivable->soitems->selectRaw('SUM(qty * price) as total')->pluck('total')
        
//         $receivable = Custinfo::latest('created_at')->first();
//         return $receivable;
//         return $receivable->soitems->every(0, 1);
        return view('accounting.receivables.index', compact('receivables'));
    }
    
    public function getSoTotalPriceByCustinfo()
    {
        return '';
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
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
    }
}
