<?php

namespace App\Http\Controllers\Purchaseorderc;

use App\Models\Purchaseorderc\Asn;
use App\Models\Purchaseorderc\Asnitem;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AsnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $asns = Asn::latest('created_at')->paginate(10);
        return view('purchaseorderc.asns.index', compact('asns'));
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

    public function packingstore(Request $request)
    {
        //
        $input = $request->all();
        if (strlen($input['items_string']) < 1)
            dd('未设置任何打包数据，保存ASN失败。');

        $number = Carbon::now()->toDateTimeString();
        $input['number'] = $number;

        $asn = Asn::create($input);

        if (isset($asn))
        {
            $asnitems = json_decode($input['items_string']);
            foreach ($asnitems as $asnitem) {
                $item_array = json_decode(json_encode($asnitem), true);
                $item_array['asn_id'] = $asn->id;

                Asnitem::create($item_array);
            }
        }
        return redirect('purchaseorderc/asns');
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
    }

    public function detail($id)
    {
        $asnitems = Asnitem::where('asn_id', $id)->paginate(10);
        return view('purchaseorderc.asnitems.index', compact('asnitems', 'id'));
    }
}
