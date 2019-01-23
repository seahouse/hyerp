<?php

namespace App\Http\Controllers\Purchaseorderc;

use App\Models\Purchaseorderc\Poheadc;
use App\Models\Purchaseorderc\Poitemc;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Log, Datatables;

class PurchaseordercController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $purchaseorders = Poheadc::latest('created_at')->paginate(10);
        return view('purchaseorderc.purchaseordercs.index', compact('purchaseorders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('purchaseorderc.purchaseordercs.create');
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
        $input = $request->all();
//        $input = HelperController::skipEmptyValue($input);



        $purchaseorder = Poheadc::create($input);






        return redirect('purchaseorderc/purchaseordercs');
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
        $purchaseorder = Poheadc::findOrFail($id);
//        var_dump($report->active);
        return view('purchaseorderc.purchaseordercs.edit', compact('purchaseorder'));
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
        $poitems = Poitemc::latest('created_at')->where('poheadc_id', $id)->paginate(10);
        return view('purchaseorderc.poitemcs.index', compact('poitems', 'id'));
    }

    public function detailjson(Request $request, $id)
    {
        $query = Poitemc::whereRaw('poheadc_id=' . $id);
//        $query->where('status', 0);
//        if ($request->has('sohead_id'))
//            $query->where('sohead_id', $request->get('sohead_id'));
//        elseif ($sohead_id > 0)
//            $query->where('sohead_id', $sohead_id);
//        elseif (strlen($factory) > 0)
//            $query->where('productioncompany', 'like', '%' . $factory . '%');
//        elseif ($project_id > 0)
//        {
//            $sohead_ids = Salesorder_hxold::where('project_id', $project_id)->pluck('id');
//            $query->whereIn('sohead_id', $sohead_ids);
//        }
//
//        if ($request->has('project_id'))
//        {
//            $sohead_ids = Salesorder_hxold::where('project_id', $request->get('project_id'))->pluck('id');
//            $query->whereIn('sohead_id', $sohead_ids);
//        }
//
//        if ($request->has('issuedrawingdatestart') && $request->has('issuedrawingdateend')) {
//            $query->whereRaw('issuedrawings.created_at between \'' . $request->get('issuedrawingdatestart') . '\' and \'' . $request->get('issuedrawingdateend') . '\'');
//        }
//
//        $query->leftJoin('users', 'users.id', '=', 'issuedrawings.applicant_id');



        return Datatables::of($query->select('poitemcs.*'))
            ->addColumn('packedcount', function () {
                return 0.0;
            })
            ->addColumn('packingcount', function () {
                return '<input class="form-control" name="key" type="text">';
            })
//            ->filterColumn('created_at', function ($query) use ($request) {
//                $keyword = $request->get('search')['value'];
//                $query->whereRaw('CONVERT(varchar(100), issuedrawings.created_at, 23) like \'%' . $keyword . '%\'');
//            })
//            ->filterColumn('applicant', function ($query) use ($request) {
//                $keyword = $request->get('search')['value'];
//                $query->whereRaw('users.name like \'%' . $keyword . '%\'');
//            })
//            ->editColumn('created_at', '{{ substr($created_at, 0, 10) }}' )
            ->make(true);
    }

    public function packing($id)
    {
        $poitems = Poitemc::where('poheadc_id', $id)->paginate(10);
        return view('purchaseorderc.purchaseordercs.packing', compact('poitems', 'id'));
    }
}
