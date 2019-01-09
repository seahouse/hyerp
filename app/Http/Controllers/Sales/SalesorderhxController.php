<?php

namespace App\Http\Controllers\Sales;

use App\Models\Sales\Dwgbom_hx;
use App\Models\Sales\Salesorder_hxold;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class SalesorderhxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $request = request();
        return $this->search($request);

//        $salesorders = Salesorder_hxold::where('status', '<>', -10)->orderBy('id', 'desc')->paginate(10);
//        return view('sales.salesorderhx.index', compact('salesorders'));
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
    }


    public function search(Request $request)
    {
        //
//        dd($request->all());
        $inputs = $request->all();
        $key = $request->input('key');

        $query = Salesorder_hxold::where('status', '<>', -10)->orderBy('id', 'desc');
        if (strlen($key))
        {
            $query->where(function ($query) use ($key) {
                $query->where('number', 'like', '%' . $key. '%')
                    ->orWhere('descrip', 'like', '%' . $key. '%');
            });
        }

        $salesorders = $query->select()->paginate(10);
        return view('sales.salesorderhx.index', compact('salesorders', 'inputs'));
    }

    public function checktaxrateinput($id)
    {
        //
        $exitCode = Artisan::call('reminder:taxrateinput', [
//            '--debug' => true,
            '--sohead_id' => $id,
        ]);
        dd('检查完成, 请查看钉钉消息.(' . $exitCode . ')');
    }

    public function dwgbom($id)
    {
        //
        $dwgboms = Dwgbom_hx::where('sohead_id', $id)->paginate(10);
        return view('sales.salesorderhx.dwgbom', compact('dwgboms', 'id'));
    }
}
