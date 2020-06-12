<?php

namespace App\Http\Controllers\Basic;

use App\Models\Basic\Constructionbidinformationfield;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Log;

class ConstructionbidinformationfieldController extends Controller
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
        $constructionbidinformationfields = $this->searchrequest($request);
//        $dtlogs = Dtlog::latest('create_time')->paginate(15);
        return view('basic.constructionbidinformationfields.index', compact('constructionbidinformationfields', 'inputs'));
    }

    public function search(Request $request)
    {
//        $key = $request->input('key');
        $inputs = $request->all();
        $constructionbidinformationfields = $this->searchrequest($request);

        return view('basic.constructionbidinformationfields.index', compact('constructionbidinformationfields', 'inputs'));
    }

    public function searchedittable(Request $request)
    {
//        $key = $request->input('key');
        $inputs = $request->all();
        $constructionbidinformationfields = $this->searchrequest($request);

        return view('basic.constructionbidinformationfields.edittable', compact('constructionbidinformationfields', 'inputs'));
    }

    public function searchrequest($request)
    {
//        dd($request->all());
        $query = Constructionbidinformationfield::orderBy('sort');

        if ($request->has('createdatestart') && $request->has('createdateend'))
        {
            $query->whereRaw("DATEDIFF(DAY, create_time, '" . $request->input('createdatestart') . "') <= 0 and DATEDIFF(DAY, create_time, '" . $request->input('createdateend') . "') >=0");

        }

        if ($request->has('unit'))
        {
            $query->where('unit', $request->input('unit'));
        }

        if ($request->has('projecttype'))
        {
            $query->where('projecttype', $request->input('projecttype'));
        }

        if ($request->has('key') && strlen($request->input('key')) > 0)
        {
            $query->where('name', 'like', '%' . $request->input('key') . '%');
        }

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

        $items = $query->select('constructionbidinformationfields.*')
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
        return view('basic.constructionbidinformationfields.create');
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
        $this->validate($request, [
            'projecttype'               => 'required',
        ]);
        $input = $request->all();
        $input['unitprice'] = floatval($input['unitprice']);
        Constructionbidinformationfield::create($input);
        return redirect('basic/constructionbidinformationfields');
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
        $constructionbidinformationfield = Constructionbidinformationfield::findOrFail($id);
        return view('basic.constructionbidinformationfields.edit', compact('constructionbidinformationfield'));
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
        $this->validate($request, [
            'projecttype'               => 'required',
        ]);

        $inputs = $request->all();
        $inputs['unitprice'] = floatval($inputs['unitprice']);
//        dd($inputs);
        $constructionbidinformationfield = Constructionbidinformationfield::findOrFail($id);
        $constructionbidinformationfield->update($inputs);
        return redirect('basic/constructionbidinformationfields');
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
        Constructionbidinformationfield::destroy($id);
        return redirect('basic/constructionbidinformationfields');
    }

    public function getfieldsbyprojecttype(Request $request)
    {
        //
        $strhtml = "";
        if ($request->has('projecttype') && strlen($request->input('projecttype')) > 0)
        {
            $fields = Constructionbidinformationfield::where('projecttype', $request->input('projecttype'))->pluck('name');
            foreach ($fields as $field)
                $strhtml .= "<option value='" . $field . "'>" . $field . "</option>";
            Log::info($fields);
            Log::info($strhtml);
        }
        return $strhtml;
    }

    public function edittable()
    {
        $request = request();
        $inputs = $request->all();
        $constructionbidinformationfields = $this->searchrequest($request);
//        dd(json_encode($biddinginformations->toArray()['data']) );
        return view('basic.constructionbidinformationfields.edittable', compact('constructionbidinformationfields', 'inputs'));
    }

    public function updateedittable(Request $request)
    {
//        Log::info($request->all());
//        $inputs = $request->all();
//        dd($inputs);
        $id = $request->get('pk');
        $constructionbidinformationfield = Constructionbidinformationfield::findOrFail($id);
        $name = $request->get('name');
        $value = $request->get('value');
        $constructionbidinformationfield->$name = $value;
        $constructionbidinformationfield->save();
        return 'success';
    }
}
