<?php

namespace App\Http\Controllers\Basic;

use App\Models\Basic\Constructionbidinformation;
use App\Models\Basic\Constructionbidinformationfield;
use App\Models\Basic\Constructionbidinformationfieldtype;
use App\Models\Basic\Constructionbidinformationitem;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ConstructionbidinformationController extends Controller
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
        $inputs = $request->all();
        $constructionbidinformations = $this->searchrequest($request)->paginate(15);
//        $dtlogs = Dtlog::latest('create_time')->paginate(15);
        return view('basic.constructionbidinformations.index', compact('constructionbidinformations', 'inputs'));
    }

    public function searchrequest($request)
    {
//        dd($request->all());
        $query = Constructionbidinformation::latest();

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
            $query->whereExists(function ($query) use ($request) {
                $query->select(DB::raw(1))
                    ->from('biddinginformationitems')
                    ->whereRaw('biddinginformationitems.biddinginformation_id=biddinginformations.id and biddinginformationitems.value like \'%' . $request->input('key') . '%\'');
            });
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

        $items = $query->select('constructionbidinformations.*');

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

    public function storebyprojecttypes(Request $request)
    {
        //
        $inputs = $request->all();
//        dd($inputs);
        if (!$request->has('projecttypes') || $request->input('projecttypes') == '')
            dd('没有选择项目类型');

        $seqnumber = Constructionbidinformation::where('year', Carbon::today()->year)->max('digital_number');
        $seqnumber += 1;
        $seqnumber = str_pad($seqnumber, 4, 0, STR_PAD_LEFT);

        $number = Carbon::today()->format('Y') . '-' . $seqnumber;
        $data = [
            'number'    => $number,
            'year'      => Carbon::today()->year,
            'digital_number'    => isset($seqnumber) ? $seqnumber : 1,
        ];
        $constructionbidinformation = Constructionbidinformation::create($data);
        if (isset($constructionbidinformation))
        {
            $projecttypes = explode(',', $request->input('projecttypes'));
            foreach ($projecttypes as $projecttype)
            {
                Constructionbidinformationfieldtype::create([
                    'constructionbidinformation_id'     => $constructionbidinformation->id,
                    'constructionbidinformation_fieldtype'  => $projecttype,
                ]);
            }
            $constructionbidinformationfields = Constructionbidinformationfield::whereIn('projecttype', $projecttypes)->orderBy('sort')->get();
            foreach ($constructionbidinformationfields as $constructionbidinformationfield)
            {
                Constructionbidinformationitem::create([
                    'constructionbidinformation_id' => $constructionbidinformation->id,
                    'key' => $constructionbidinformationfield->name,
                    'sort' => $constructionbidinformationfield->sort,
//                    'type' => $biddinginformationdefinefield->type,
                ]);
            }
        }

        return redirect('basic/constructionbidinformations/' . $constructionbidinformation->id . '/edit');
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
        $constructionbidinformation = Constructionbidinformation::findOrFail($id);
        return view('basic.constructionbidinformations.edit', compact('constructionbidinformation'));
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
        $constructionbidinformation = Constructionbidinformation::findOrFail($id);
//        $constructionbidinformation->remark = $request->has('remark') ? $request->input('remark') : '';
//        $constructionbidinformation->save();

//        $biddinginformation->update($request->all());
        $inputs = $request->all();
//        dd($inputs);
        $remark_suffix = '_remark';
        if (isset($constructionbidinformation))
        {
            $constructionbidinformation_items = json_decode($inputs['items_string']);
            foreach ($constructionbidinformation_items as $constructionbidinformation_item) {
                $constructionbidinformationitem = Constructionbidinformationitem::find($constructionbidinformation_item->constructionbidinformationitem_id);
                if (isset($constructionbidinformationitem))
                {
//                    $item_array = json_decode(json_encode($issuedrawingcabinet_item), true);
//                    $item_array['issuedrawing_id'] = $issuedrawing->id;
//                    $issuedrawingcabinet = Issuedrawingcabinet::create($item_array);
                    $constructionbidinformationitem->purchaser = $constructionbidinformation_item->purchaser;
                    $constructionbidinformationitem->specification_technicalrequirements = $constructionbidinformation_item->specification_technicalrequirements;
                    $constructionbidinformationitem->value_line1 = doubleval($constructionbidinformation_item->value_line1);
                    $constructionbidinformationitem->value_line2 = doubleval($constructionbidinformation_item->value_line2);
                    $constructionbidinformationitem->value_line3 = doubleval($constructionbidinformation_item->value_line3);
                    $constructionbidinformationitem->value_line4 = doubleval($constructionbidinformation_item->value_line4);
                    $constructionbidinformationitem->unit = $constructionbidinformation_item->unit;
                    $constructionbidinformationitem->remark = $constructionbidinformation_item->remark;
                    $constructionbidinformationitem->save();
                }
            }

//            foreach ($inputs as $key => $value)
//            {
//                if (!(substr($key, -strlen($remark_suffix)) === $remark_suffix))
//                {
//                    $biddinginformationitem = Biddinginformationitem::where('biddinginformation_id', $id)->where('key', $key)->first();
//                    if (isset($biddinginformationitem))
//                    {
//                        $oldvalue = $biddinginformationitem->value;
//                        $remark = isset($inputs[$key . $remark_suffix]) ? $inputs[$key . $remark_suffix] : '';
//                        if ($biddinginformationitem->update(['value' => $value, 'remark' => $remark]))
//                        {
//                            if ($oldvalue != $value)
//                            {
//                                $projectname = '';
//                                $biddinginformationitem_mingcheng = Biddinginformationitem::where('biddinginformation_id', $id)->where('key', '名称')->first();
//                                if (isset($biddinginformationitem_mingcheng))
//                                    $projectname = $biddinginformationitem_mingcheng->value;
//
//                                $msg = '[' . $projectname . ']项目[' . $biddinginformation->number . ']的[' . $biddinginformationitem->key .']字段内容已修改。原内容：' . $oldvalue . '，新内容：' . $value;
//                                $data = [
//                                    'msgtype'       => 'text',
//                                    'text' => [
//                                        'content' => $msg
//                                    ]
//                                ];
//
////                            $dtusers = Dtuser::where('user_id', 126)->orWhere('user_id', 126)->pluck('userid');        // test
//                                $dtusers = Dtuser::where('user_id', 2)->orWhere('user_id', 64)->pluck('userid');             // WuHL, Zhoub
//                                $useridList = implode(',', $dtusers->toArray());
////                            dd(implode(',', $dtusers->toArray()));
//                                if ($dtusers->count() > 0)
//                                {
//                                    $agentid = config('custom.dingtalk.agentidlist.bidding');
//                                    DingTalkController::sendWorkNotificationMessage($useridList, $agentid, json_encode($data));
//                                }
//                            }
//                        }
//                    }
//                }
//            }

        }
        return redirect('basic/constructionbidinformations');
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
        Constructionbidinformation::destroy($id);
        return redirect('basic/constructionbidinformations');
    }

    public function edittable($id)
    {
        $constructionbidinformation = Constructionbidinformation::findOrFail($id);
//        $biddinginformationdefinefields = Biddinginformationdefinefield::orderBy('sort')->pluck('name');
//        dd(json_encode($biddinginformations->toArray()['data']) );
        return view('basic.constructionbidinformations.edittable', compact('constructionbidinformation'));
    }
}
