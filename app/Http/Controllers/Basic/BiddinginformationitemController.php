<?php

namespace App\Http\Controllers\Basic;

use App\Models\Basic\Biddinginformationitem;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables, Log;

class BiddinginformationitemController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $request = request();
        $inputs = $request->all();
        $biddinginformations = $this->searchrequest($request);
//        $dtlogs = Dtlog::latest('create_time')->paginate(15);
        return view('basic.biddinginformations.index', compact('biddinginformations', 'inputs'));
    }

    public function searchrequest($request)
    {
//        dd($request->all());
        $query = Biddinginformation::latest();

        if ($request->has('createdatestart') && $request->has('createdateend'))
        {
            $query->whereRaw("DATEDIFF(DAY, create_time, '" . $request->input('createdatestart') . "') <= 0 and DATEDIFF(DAY, create_time, '" . $request->input('createdateend') . "') >=0");

        }

        if ($request->has('creator_name'))
        {
            $query->where('creator_name', $request->input('creator_name'));
        }

        if ($request->has('key') && strlen($request->input('key')) > 0)
        {
            $query->where('remark', 'like', '%' . $request->input('key') . '%');
        }

//        // xmjlsgrz_sohead_id
//        if ($request->has('xmjlsgrz_sohead_id') && $request->input('xmjlsgrz_sohead_id') > 0)
//        {
//            $query->where('xmjlsgrz_sohead_id', $request->input('xmjlsgrz_sohead_id'));
//        }

        // xmjlsgrz_project_id
        if ($request->has('xmjlsgrz_project_id') && $request->input('xmjlsgrz_project_id') > 0)
        {
            $soheadids = Salesorder_hxold::where('project_id', $request->input('xmjlsgrz_project_id'))->pluck('id');
//            dd($soheadids);
            $query->whereIn('xmjlsgrz_sohead_id', $soheadids);
        }

        // other
        if ($request->has('other'))
        {
            if ($request->input('other') == 'xmjlsgrz_sohead_id_undefined')
            {
                $query->where(function ($query) {
                    $query->whereNull('xmjlsgrz_sohead_id')
                        ->orWhere('xmjlsgrz_sohead_id', '<', 1);
                });
            }
            elseif ($request->input('other') == 'btn_xmjlsgrz_peoplecount_undefined')
            {
                $xmjlsgrz_peoplecount_keys = config('custom.dingtalk.dtlogs.peoplecount_keys.xmjlsgrz');
                Log::info('(select SUM(convert(int, value)) from dtlogitems	where dtlogs.id=dtlogitems.dtlog_id and value not like \'%[^0-9]%\' and dtlogitems.[key] in (\'' . implode(",", $xmjlsgrz_peoplecount_keys) . '\')) is null');
                $query->whereRaw('(select SUM(convert(int, value)) from dtlogitems	where dtlogs.id=dtlogitems.dtlog_id and value not like \'%[^0-9]%\' and dtlogitems.[key] in (\'' . implode("','", $xmjlsgrz_peoplecount_keys) . '\')) is null');
//                $query->leftJoin('dtlogitems', 'dtlogs.id', '=', 'dtlogitems.dtlog_id');
//                if (isset($dtlogitem) && $request->has('xmjlsgrz_peoplecount'))
//                {
//                    $dtlogitem->value = $request->input('xmjlsgrz_peoplecount');
//                    $dtlogitem->save();
//                }
            }
        }

        $items = $query->select('biddinginformations.*')
            ->paginate(15);

        return $items;
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
        $biddinginformation = Biddinginformation::findOrFail($id);
        return view('basic.biddinginformations.show', compact('biddinginformation'));
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
        $biddinginformation = Biddinginformation::findOrFail($id);
        return view('basic.biddinginformations.edit', compact('biddinginformation'));
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
        Biddinginformation::destroy($id);
        return redirect('basic/biddinginformationitems');
    }

    public function jsondata(Request $request, $sohead_id = 0, $factory = '', $project_id = 0)
    {
        Log::info($request->all());
        $query = Biddinginformationitem::whereRaw('1=1');
//        $query->where('status', 0);
        if ($request->has('biddinginformation_id'))
            $query->where('biddinginformation_id', $request->get('biddinginformation_id'));
        elseif ($sohead_id > 0)
            $query->where('sohead_id', $sohead_id);
        elseif (strlen($factory) > 0)
            $query->where('productioncompany', 'like', '%' . $factory . '%');

        if ($project_id > 0)
        {
            $sohead_ids = Salesorder_hxold::where('project_id', $project_id)->pluck('id');
            $query->whereIn('sohead_id', $sohead_ids);
        }

        if ($request->has('project_id'))
        {
            $sohead_ids = Salesorder_hxold::where('project_id', $request->get('project_id'))->pluck('id');
            $query->whereIn('sohead_id', $sohead_ids);
        }

        if ($request->has('issuedrawingdatestart') && $request->has('issuedrawingdateend')) {
            $query->whereRaw('issuedrawings.created_at between \'' . $request->get('issuedrawingdatestart') . '\' and \'' . $request->get('issuedrawingdateend') . '\'');
        }

//        $query->leftJoin('users', 'users.id', '=', 'issuedrawings.applicant_id');


        return Datatables::of($query->select(['biddinginformationitems.*']))
//            ->filterColumn('created_at', function ($query) use ($request) {
//                $keyword = $request->get('search')['value'];
//                $query->whereRaw('CONVERT(varchar(100), issuedrawings.created_at, 23) like \'%' . $keyword . '%\'');
//            })
//            ->filterColumn('applicant', function ($query) use ($request) {
//                $keyword = $request->get('search')['value'];
//                $query->whereRaw('users.name like \'%' . $keyword . '%\'');
//            })
//            ->editColumn('created_at1', '{{ substr($created_at, 0, 10) }}' )
//            ->filter(function ($query) use ($request) {
//                if ($request->has('issuedrawingdatestart') && $request->has('issuedrawingdateend')) {
//                    $query->whereRaw('issuedrawings.created_at between \'' . $request->get('issuedrawingdatestart') . '\' and \'' . $request->get('issuedrawingdateend') . '\'');
//                }
//            })
//            ->addColumn('applicant', function (Issuedrawing $issuedrawing) {
//                return $issuedrawing->applicant->name;
//            })
//            ->addColumn('bonus', function (Receiptpayment_hxold $receiptpayment) {
//                return $receiptpayment->amount * $receiptpayment->sohead->getBonusfactorByPolicy() * array_first($receiptpayment->sohead->getAmountpertenthousandBySohead())->amountpertenthousandbysohead;
//            })
//                ->orderColumn('created_at')
            ->make(true);
    }
}
