<?php

namespace App\Http\Controllers\Basic;

use App\Http\Controllers\DingTalkController;
use App\Models\Basic\Biddinginformation;
use App\Models\Basic\Biddinginformationdefinefield;
use App\Models\Basic\Biddinginformationeditem;
use App\Models\Basic\Biddinginformationfieldtype;
use App\Models\Basic\Biddinginformationitem;
use App\Models\Basic\Biddinginformationitemmodifylog;
use App\Models\Sales\Salesorder_hxold;
use App\Models\System\Dtuser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use PHPExcel_RichText;

class BiddinginformationController extends Controller
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
        $biddinginformations = $this->searchrequest($request)->paginate(15);
        //        $dtlogs = Dtlog::latest('create_time')->paginate(15);
        return view('basic.biddinginformations.index', compact('biddinginformations', 'inputs'));
    }

    public function search(Request $request)
    {
        $inputs = $request->all();
        $biddinginformations = $this->searchrequest($request)->paginate(15);
        //        $purchaseorders = Purchaseorder_hxold::whereIn('id', $paymentrequests->pluck('pohead_id'))->get();
        //        $totalamount = Paymentrequest::sum('amount');

        return view('basic.biddinginformations.index', compact('biddinginformations', 'inputs'));
    }

    public function searchrequest($request)
    {
        //        dd($request->all());
        $query = Biddinginformation::latest();

        if ($request->has('createdatestart') && $request->has('createdateend')) {
            $query->whereRaw("DATEDIFF(DAY, create_time, '" . $request->input('createdatestart') . "') <= 0 and DATEDIFF(DAY, create_time, '" . $request->input('createdateend') . "') >=0");
        }

        if ($request->has('creator_name')) {
            $query->where('creator_name', $request->input('creator_name'));
        }

        if ($request->has('key') && strlen($request->input('key')) > 0) {
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
        if ($request->has('xmjlsgrz_project_id') && $request->input('xmjlsgrz_project_id') > 0) {
            $soheadids = Salesorder_hxold::where('project_id', $request->input('xmjlsgrz_project_id'))->pluck('id');
            //            dd($soheadids);
            $query->whereIn('xmjlsgrz_sohead_id', $soheadids);
        }

        // other
        if ($request->has('other')) {
            if ($request->input('other') == 'xmjlsgrz_sohead_id_undefined') {
                $query->where(function ($query) {
                    $query->whereNull('xmjlsgrz_sohead_id')
                        ->orWhere('xmjlsgrz_sohead_id', '<', 1);
                });
            } elseif ($request->input('other') == 'btn_xmjlsgrz_peoplecount_undefined') {
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

        $items = $query->select('biddinginformations.*');

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
        $biddinginformationdefinefields = Biddinginformationdefinefield::orderBy('sort')->get();
        return view('basic.biddinginformations.create', compact('biddinginformationdefinefields'));
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
        $inputs = $request->all();
        //        dd($inputs);
        $seqnumber = Biddinginformation::where('year', Carbon::today()->year)->max('digital_number');
        $seqnumber += 1;
        $seqnumber = str_pad($seqnumber, 4, 0, STR_PAD_LEFT);

        $number = Carbon::today()->format('Y') . '-' . $seqnumber;
        $data = [
            'number'    => $number,
            'year'      => Carbon::today()->year,
            'digital_number'    => isset($seqnumber) ? $seqnumber : 1,
            'biddingprojectid'  => isset($inputs['biddingprojectid']) ? $inputs['biddingprojectid'] : '',
        ];
        $biddinginformation = Biddinginformation::create($data);
        if (isset($biddinginformation)) {
            $sort = 0;
            $type = 0;
            $remark_suffix = '_remark';
            foreach ($inputs as $key => $value) {
                if ($key != '_token') {
                    $biddinginformationdefinefield = Biddinginformationdefinefield::where('name', $key)->first();
                    if (isset($biddinginformationdefinefield)) {
                        $sort = $biddinginformationdefinefield->sort;
                        $type = $biddinginformationdefinefield->type;
                    }
                    $remark = isset($inputs[$key . $remark_suffix]) ? $inputs[$key . $remark_suffix] : '';
                    Biddinginformationitem::create([
                        'biddinginformation_id' => $biddinginformation->id,
                        'key' => $key,
                        'value' => $value,
                        'remark' => $remark,
                        'sort' => $sort,
                        'type' => $type,
                    ]);
                }
            }
        }

        return redirect('basic/biddinginformations');
    }

    public function storebyprojecttypes(Request $request)
    {
        //
        $inputs = $request->all();
        //        dd($inputs);
        if (!$request->has('projecttypes') || $request->input('projecttypes') == '')
            dd('没有选择项目类型');

        $seqnumber = Biddinginformation::where('year', Carbon::today()->year)->max('digital_number');
        $seqnumber += 1;
        $seqnumber = str_pad($seqnumber, 4, 0, STR_PAD_LEFT);

        $number = Carbon::today()->format('Y') . '-' . $seqnumber;
        $data = [
            'number'    => $number,
            'year'      => Carbon::today()->year,
            'digital_number'    => isset($seqnumber) ? $seqnumber : 1,
        ];
        $biddinginformation = Biddinginformation::create($data);
        if (isset($biddinginformation)) {
            $projecttypes = explode(',', $request->input('projecttypes'));
            foreach ($projecttypes as $projecttype) {
                Biddinginformationfieldtype::create([
                    'biddinginformation_id'     => $biddinginformation->id,
                    'biddinginformation_fieldtype'  => $projecttype,
                ]);
            }
            $biddinginformationdefinefields = Biddinginformationdefinefield::whereIn('projecttype', $projecttypes)->orderBy('sort')->get();
            foreach ($biddinginformationdefinefields as $biddinginformationdefinefield) {
                Biddinginformationitem::create([
                    'biddinginformation_id' => $biddinginformation->id,
                    'key' => $biddinginformationdefinefield->name,
                    'value' => '',
                    'remark' => '',
                    'sort' => $biddinginformationdefinefield->sort,
                    'type' => $biddinginformationdefinefield->type,
                ]);
            }
        }

        return redirect('basic/biddinginformations/' . $biddinginformation->id . '/edit');
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
        //        dd($biddinginformation->biddinginformationitems()->orderBy('id')->get());
        return view('basic.biddinginformations.show', compact('biddinginformation'));
    }

    public function xyshow($id)
    {
        //
        $biddinginformation = Biddinginformation::findOrFail($id);
        //        dd($biddinginformation->biddinginformationitems()->orderBy('id')->get());
        return view('basic.biddinginformations.xyshow', compact('biddinginformation'));
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
        if ($biddinginformation->closed == 1 && !Auth::user()->isSuperAdmin())
            dd('该记录已关闭，无法编辑。');

        //        $biddinginformationitem = $biddinginformation->biddinginformationitems->where('key', '华光')->first();
        //        dd($biddinginformationitem->biddinginformationitemmodifylogs->where('isclarify', '1'));
        return view('basic.biddinginformations.edit', compact('biddinginformation'));
    }

    public function xyedit($id)
    {
        //
        $biddinginformation = Biddinginformation::findOrFail($id);
        if ($biddinginformation->closed == 1 && !Auth::user()->isSuperAdmin())
            dd('该记录已关闭，无法编辑。');

        foreach ($biddinginformation->biddinginformationitems as $biddinginformationitem) {
            $biddinginformationeditem = Biddinginformationeditem::where('biddinginformation_id', $biddinginformation->id)->where('key', $biddinginformationitem->key)->first();
            if (!isset($biddinginformationeditem))
                Biddinginformationeditem::create($biddinginformationitem->toArray());
            //                dd($biddinginformationitem->toArray());
        }

        return view('basic.biddinginformations.xyedit', compact('biddinginformation'));
    }

    public function updatesaleorderid(Request $request)
    {
        //
        $input = $request->all();
        //        log::info($input);
        $id = $input['informationid'];
        $biddinginformation = Biddinginformation::findOrFail($id);
        $retcode = $biddinginformation->update(['sohead_id' => $input['soheadid']]);
        if ($retcode >= 0)
            $data = [
                'errorcode' => 0,
                'errormsg' => 'success',
            ];
        else
            $data = [
                'errorcode' => $retcode,
                'errormsg' => '更新失败',
            ];
        //        Log::Info($retcode);
        return response()->json($data);
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
        //        dd($request->all());
        $biddinginformation = Biddinginformation::findOrFail($id);
        $biddinginformation->remark = $request->has('remark') ? $request->input('remark') : '';
        $biddinginformation->biddingprojectid = $request->has('biddingprojectid') ? $request->input('biddingprojectid') : '';
        $biddinginformation->save();

        //        $biddinginformation->update($request->all());
        $inputs = $request->all();
        //        dd($inputs);
        $remark_suffix = '_remark';
        $isclarify_suffix = '_isclarify';
        $clarify_msgs = [];
        $projectname = '';
        foreach ($inputs as $key => $value) {
            if (!(substr($key, -strlen($remark_suffix)) === $remark_suffix) && !(substr($key, -strlen($isclarify_suffix)) === $isclarify_suffix)) {
                $biddinginformationitem = Biddinginformationitem::where('biddinginformation_id', $id)->where('key', $key)->first();
                if (isset($biddinginformationitem)) {
                    $oldvalue = $biddinginformationitem->value;
                    $remark = isset($inputs[$key . $remark_suffix]) ? $inputs[$key . $remark_suffix] : '';
                    $isclarify = isset($inputs[$key . $isclarify_suffix]) ? true : false;
                    //                    dd($key . ':' . $inputs[$key . $remark_suffix]);
                    if ($biddinginformationitem->update(['value' => $value, 'remark' => $remark])) {
                        if ($oldvalue != $value) {
                            $biddinginformationitem_mingcheng = Biddinginformationitem::where('biddinginformation_id', $id)->where('key', '名称')->first();
                            if (isset($biddinginformationitem_mingcheng))
                                $projectname = $biddinginformationitem_mingcheng->value;

                            $msg = '[' . $projectname . ']项目[' . $biddinginformation->number . ']的[' . $biddinginformationitem->key . ']字段内容已修改。原内容：' . $oldvalue . '，新内容：' . $value;
                            $data = [
                                'msgtype'       => 'text',
                                'text' => [
                                    'content' => $msg
                                ]
                            ];

                            //                            $dtusers = Dtuser::where('user_id', 126)->orWhere('user_id', 126)->pluck('userid');        // test
                            $dtusers = Dtuser::where('user_id', 64)->pluck('userid');             // Zhoub
                            $useridList = implode(',', $dtusers->toArray());
                            //                            dd(implode(',', $dtusers->toArray()));
                            if ($dtusers->count() > 0) {
                                $agentid = config('custom.dingtalk.agentidlist.bidding');
                                DingTalkController::sendWorkNotificationMessage($useridList, $agentid, json_encode($data));
                            }

                            Biddinginformationitemmodifylog::create([
                                'biddinginformationitem_id' => $biddinginformationitem->id,
                                'oldvalue'      => $oldvalue,
                                'value'         => $value,
                                'isclarify'     => $isclarify,
                            ]);

                            if ($isclarify) {
                                array_push($clarify_msgs, $key . '，原内容：' . $oldvalue . '，新内容：' . $value);
                            }
                        }
                    }
                }
            }
        }

        if (count($clarify_msgs) > 0) {
            $biddinginformation->update(['remark' => $biddinginformation->remark . "\n" . Carbon::now()->toDateString() . "澄清：\n" . implode("\n", $clarify_msgs)]);

            $msg = '[' . $projectname . ']项目[' . $biddinginformation->number . ']的澄清：[' . implode("\n", $clarify_msgs);
            $data = [
                'msgtype'       => 'text',
                'text' => [
                    'content' => $msg
                ]
            ];
            $dtusers = Dtuser::where('user_id', 2)->orWhere('user_id', 64)->pluck('userid');             // WuHL, Zhoub
            $useridList = implode(',', $dtusers->toArray());
            if ($dtusers->count() > 0) {
                $agentid = config('custom.dingtalk.agentidlist.bidding');
                DingTalkController::sendWorkNotificationMessage($useridList, $agentid, json_encode($data));
            }
        }
        return redirect('basic/biddinginformations');
    }

    public function xyupdate(Request $request, $id)
    {
        //
        //        dd($request->all());
        $biddinginformation = Biddinginformation::findOrFail($id);
        $biddinginformation->remark = $request->has('remark') ? $request->input('remark') : '';
        $biddinginformation->biddingprojectid = $request->has('biddingprojectid') ? $request->input('biddingprojectid') : '';
        $biddinginformation->save();

        //        $biddinginformation->update($request->all());
        $inputs = $request->all();
        //        dd($inputs);
        $remark_suffix = '_remark';
        $isclarify_suffix = '_isclarify';
        $clarify_msgs = [];
        $projectname = '';
        foreach ($inputs as $key => $value) {
            if (!(substr($key, -strlen($remark_suffix)) === $remark_suffix) && !(substr($key, -strlen($isclarify_suffix)) === $isclarify_suffix)) {
                $biddinginformationeditem = Biddinginformationeditem::where('biddinginformation_id', $id)->where('key', $key)->first();
                if (isset($biddinginformationeditem)) {
                    $oldvalue = $biddinginformationeditem->value;
                    $remark = isset($inputs[$key . $remark_suffix]) ? $inputs[$key . $remark_suffix] : '';
                    $isclarify = isset($inputs[$key . $isclarify_suffix]) ? true : false;
                    //                    dd($key . ':' . $inputs[$key . $remark_suffix]);
                    if ($biddinginformationeditem->update(['value' => $value, 'remark' => $remark])) {
                        if ($oldvalue != $value) {
                            $biddinginformationitem_mingcheng = Biddinginformationitem::where('biddinginformation_id', $id)->where('key', '名称')->first();
                            if (isset($biddinginformationitem_mingcheng))
                                $projectname = $biddinginformationitem_mingcheng->value;

                            $msg = '[' . $projectname . ']项目[' . $biddinginformation->number . ']的[' . $biddinginformationeditem->key . ']字段内容已协议修改。原内容：' . $oldvalue . '，新内容：' . $value;
                            $data = [
                                'msgtype'       => 'text',
                                'text' => [
                                    'content' => $msg
                                ]
                            ];

                            //                            $dtusers = Dtuser::where('user_id', 126)->orWhere('user_id', 126)->pluck('userid');        // test
                            $dtusers = Dtuser::where('user_id', 64)->pluck('userid');             // Zhoub
                            $useridList = implode(',', $dtusers->toArray());
                            //                            dd(implode(',', $dtusers->toArray()));
                            if ($dtusers->count() > 0) {
                                $agentid = config('custom.dingtalk.agentidlist.bidding');
                                DingTalkController::sendWorkNotificationMessage($useridList, $agentid, json_encode($data));
                            }

                            //                            // 由于修改的不再是原表，不做记录
                            //                            Biddinginformationitemmodifylog::create([
                            //                                'biddinginformationitem_id' => $biddinginformationeditem->id,
                            //                                'oldvalue'      => $oldvalue,
                            //                                'value'         => $value,
                            //                                'isclarify'     => $isclarify,
                            //                            ]);

                            if ($isclarify) {
                                array_push($clarify_msgs, $key . '，原内容：' . $oldvalue . '，新内容：' . $value);
                            }
                        }
                    }
                }
            }
        }

        //        if (count($clarify_msgs) > 0)
        //        {
        //            $biddinginformation->update(['remark' => $biddinginformation->remark . "\n" . Carbon::now()->toDateString() . "澄清：\n" . implode("\n", $clarify_msgs)]);
        //
        //            $msg = '[' . $projectname . ']项目[' . $biddinginformation->number . ']的澄清：[' . implode("\n", $clarify_msgs);
        //            $data = [
        //                'msgtype'       => 'text',
        //                'text' => [
        //                    'content' => $msg
        //                ]
        //            ];
        //            $dtusers = Dtuser::where('user_id', 2)->orWhere('user_id', 64)->pluck('userid');             // WuHL, Zhoub
        //            $useridList = implode(',', $dtusers->toArray());
        //            if ($dtusers->count() > 0)
        //            {
        //                $agentid = config('custom.dingtalk.agentidlist.bidding');
        //                DingTalkController::sendWorkNotificationMessage($useridList, $agentid, json_encode($data));
        //            }
        //        }
        return redirect('basic/biddinginformations');
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
        return redirect('basic/biddinginformations');
    }

    public function import()
    {
        //
        return view('basic.biddinginformations.import');
    }

    public function importstore(Request $request)
    {
        //
        $this->validate($request, [
            //            'salary_date'       => 'required',
            //            'itemtype'                    => 'required',
            //            'expirationdate'             => 'required',
            //            'sohead_id'                   => 'required|integer|min:1',
            //            'items_string'               => 'required',
            //            'detailuse'               => 'required',
        ]);

        $file = $request->file('file');
        //        dd($file->getRealPath());
        //        $file = array_get($input,'file');
        //        dd($file->public_path());
        //        Log::info($request->getSession().getServletContext()->getReadPath("/xx"));


        // !! set config/excel.php
        // 'force_sheets_collection' => true,   // !!
        Log::info('import start.');
        //        Excel::filter('chunk')->load($file->getRealPath())->chunk(250, function($results)
        //        {
        //            foreach($results as $row)
        //            {
        //                // do stuff
        //            }
        //        });
        //        return redirect('basic/biddinginformations');

        Excel::load($file->getRealPath(), function ($reader) use ($request) {
            $biddinginformationdefinefields = Biddinginformationdefinefield::all();

            $objExcel = $reader->getExcel();
            $sheet = $objExcel->getSheetByName('项目明细');
            $sheet2 = $objExcel->getSheetByName('汇总表');
            if (isset($sheet))
            //            for ($i = 0; $i < $objExcel->getSheetCount(); $i++)
            {
                //                $sheet = $objExcel->getSheet($i);
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                $highestColumn++;
                $highestRow2 = $sheet2->getHighestRow();
                $highestColumn2 = $sheet2->getHighestColumn();
                $highestColumn2++;

                //  Loop through each row of the worksheet in turn
                $keys = [];
                $keys2 = [];
                //  Read a row of data into an array
                $rowData = $sheet->rangeToArray(
                    'A' . 1 . ':' . $highestColumn . 1,
                    NULL,
                    TRUE,
                    FALSE
                );
                // 第一行，关键字
                $keys = $rowData[0];

                if (isset($sheet2)) {
                    //  Read a row of data into an array
                    $rowData2 = $sheet2->rangeToArray(
                        'A' . 1 . ':' . $highestColumn2 . 1,
                        NULL,
                        TRUE,
                        FALSE
                    );

                    // 第一行，关键字
                    $keys2 = $rowData2[0];
                }

                $hasFoundRow2 = [];             // 汇总表已经被找到的行
                $chunkcount = 100;
                for ($row = 2; $row <= $highestRow; $row++) {
                    Log::info($row);
                    //                    if ($row == 1)
                    //                    {
                    //                        //  Read a row of data into an array
                    //                        $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                    //                            NULL, TRUE, FALSE);
                    //
                    //                        // 第一行，关键字
                    //                        $keys = $rowData[0];
                    //                    }
                    //                    else
                    {
                        $input = [];
                        $index = 0;
                        //                        foreach ($keys as $key => $value)
                        //                        {
                        //                            $input[$value] = $rowData[0][$key];
                        //                        }
                        //                        $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                        //                            NULL, TRUE, FALSE);
                        //                        dd($rowData);
                        for ($colIndex = 'A'; $colIndex != $highestColumn; $colIndex++) {
                            // 组装单元格标识  A1  A2
                            $addr = $colIndex . $row;
                            // 获取单元格内容
                            $cell = $sheet->getCell($addr)->getFormattedValue();        // 日期格式，可以用这个函数获取到期望的格式
                            //                            if ($colIndex == 'D')
                            //                                dd($cell . ':' . $sheet->getCell($addr)->getDataType());
                            //                            $cell = $sheet->getCell($addr)->getValue();
                            //                            $cell = $sheet->getCell($addr)->getCalculatedValue();
                            //富文本转换字符串
                            if ($cell instanceof PHPExcel_RichText) {
                                $cell = $cell->__toString();
                            }
                            $comment = $sheet->getComment($addr)->getText()->getPlainText();
                            //                            dd($comment);
                            //                            if(empty($cell)){
                            //                                continue ;
                            //                            }
                            $input[$keys[$index]] = [$cell, $comment];
                            $index++;
                        }
                        //                        dd($input);

                        if (array_key_exists('名称', $input) && !empty($input['名称'][0])) {
                            // 查找汇总表里的数据
                            if (isset($sheet2)) {
                                for ($row2 = 2; $row2 <= $highestRow2; $row2++) {
                                    if (in_array($row2, $hasFoundRow2))
                                        continue;

                                    if ($row2 == 1) {
                                        //                                    //  Read a row of data into an array
                                        //                                    $rowData2 = $sheet2->rangeToArray('A' . $row2 . ':' . $highestColumn2 . $row2,
                                        //                                        NULL, TRUE, FALSE);
                                        //
                                        //                                    // 第一行，关键字
                                        //                                    $keys2 = $rowData2[0];
                                    } else {
                                        $input2 = [];
                                        $index = 0;
                                        for ($colIndex = 'A'; $colIndex != $highestColumn2; $colIndex++) {
                                            // 组装单元格标识  A1  A2
                                            $addr = $colIndex . $row2;
                                            // 获取单元格内容
                                            //                                            $cell = $sheet2->getCell($addr)->getFormattedValue();
                                            //                            $cell = $sheet->getCell($addr)->getValue();
                                            $cell = $sheet2->getCell($addr)->getCalculatedValue();
                                            //富文本转换字符串
                                            if ($cell instanceof PHPExcel_RichText) {
                                                $cell = $cell->__toString();
                                            }
                                            $comment = $sheet2->getComment($addr)->getText()->getPlainText();
                                            $input2[$keys2[$index]] = [$cell, $comment];
                                            $index++;
                                        }
                                        //                                        dd($input2);

                                        // 先找 编号， 找不到 就找名称
                                        // 编号是通用方法， 名称是第一次导入的方法
                                        if (array_key_exists('编号', $input) && array_key_exists('编号', $input2) && !empty($input['编号'][0]) && !empty($input2['编号'][0]) && $input2['编号'][0] == $input['编号'][0]) {
                                            $input = array_merge($input2, $input);      // 把$input放后面，重复项会使用后面的这个数组
                                            array_push($hasFoundRow2, $row2);
                                            break;
                                        } elseif (array_key_exists('名称', $input2) && !empty($input2['名称'][0]) && $input2['名称'][0] == $input['名称'][0]) {
                                            $input = array_merge($input2, $input);      // 把$input放后面，重复项会使用后面的这个数组
                                            array_push($hasFoundRow2, $row2);
                                            break;
                                        }
                                    }
                                }
                            }
                            //                            dd($input);

                            $number = '';
                            if (array_key_exists('编号', $input) && !empty($input['编号'][0]))
                                $number = $input['编号'][0];
                            else {
                                $seqnumber = Biddinginformation::where('year', Carbon::today()->year)->max('digital_number');
                                $seqnumber += 1;
                                $seqnumber = str_pad($seqnumber, 4, 0, STR_PAD_LEFT);

                                $number = Carbon::today()->format('Y') . '-' . $seqnumber;
                            }
                            $data = [
                                'number'    => $number,
                                'year'      => Carbon::today()->year,
                                'digital_number'    => isset($seqnumber) ? $seqnumber : 1,
                            ];
                            $biddinginformation = Biddinginformation::where('number', $number)->first();
                            if (!isset($biddinginformation))
                                $biddinginformation = Biddinginformation::create($data);
                            //                                dd($biddinginformation);
                            foreach ($biddinginformationdefinefields as $biddinginformationdefinefield) {
                                //                                    dd($input[$biddinginformationdefinefield->name]);

                                $itemdata = [];
                                $itemdata['biddinginformation_id']      = $biddinginformation->id;
                                $itemdata['key']                           = $biddinginformationdefinefield->name;
                                $itemdata['value']                         = isset($input[$biddinginformationdefinefield->name][0]) ? $input[$biddinginformationdefinefield->name][0] : '';
                                $itemdata['remark']                        = isset($input[$biddinginformationdefinefield->name][1]) ? $input[$biddinginformationdefinefield->name][1] : '';
                                $itemdata['sort']                          = $biddinginformationdefinefield->sort;
                                $itemdata['type']                          = $biddinginformationdefinefield->type;
                                //                                    Log::info($itemdata);
                                $biddinginformationitem = Biddinginformationitem::where('biddinginformation_id', $biddinginformation->id)->where('key', $biddinginformationdefinefield->name)->first();
                                if (isset($biddinginformationitem)) {
                                    if ($biddinginformationitem->value != $itemdata['value'])
                                        $biddinginformationitem->value = $itemdata['value'];
                                    if ($biddinginformationitem->remark != $itemdata['remark'])
                                        $biddinginformationitem->remark = $itemdata['remark'];
                                    $biddinginformationitem->save();
                                } else
                                    Biddinginformationitem::create($itemdata);
                            }
                        }
                    }
                }
            }

            //            $reader->each(function ($sheet) use (&$reader, $request, &$biddinginformationdefinefields) {
            ////                dd('sheet: ' . $sheet->getTitle());
            //                $objExcel = $reader->getExcel();
            ////                dd($objExcel->getSheet(0)->getComment('L30')->getText()->getPlainText());
            //                $sheet->each(function ($row) use (&$reader, $objExcel, $sheet, $request, &$biddinginformationdefinefields) {
            ////                    dd($objExcel->getSheetByName($sheet->getTitle())->getComment('L30')->getText()->getPlainText());
            ////                    dd($row);
            ////                        $input = array_values($row->toArray());
            //                    $input = $row->all();
            ////                    dd($input);
            ////                    if (count($input) >= 24)
            //                    {
            //                        if (!empty($input['序号']))
            //                        {
            ////                            dd($input['序号']);
            ////                            $salarysheet = Salarysheet::where('username', $input['姓名'])->where('salary_date', $request->input('salary_date'))->first();
            ////                            if (!isset($salarysheet))
            //                            {
            //                                $data = [];
            //                                $biddinginformation = Biddinginformation::create($data);
            ////                                dd($biddinginformation);
            //                                foreach ($biddinginformationdefinefields as $biddinginformationdefinefield)
            //                                {
            ////                                    dd($input[$biddinginformationdefinefield->name]);
            //                                    $itemdata = [];
            //                                    $itemdata['biddinginformation_id']      = $biddinginformation->id;
            //                                    $itemdata['key']                           = $biddinginformationdefinefield->name;
            //                                    $itemdata['value']                         = isset($input[$biddinginformationdefinefield->name]) ? $input[$biddinginformationdefinefield->name] : '';
            //                                    $itemdata['sort']                          = $biddinginformationdefinefield->sort;
            //                                    $itemdata['type']                          = $biddinginformationdefinefield->type;
            ////                                    Log::info($itemdata);
            //                                    Biddinginformationitem::create($itemdata);
            //                                }
            //                            }
            //                        }
            //                    }
            //                });
            //            });

            $objExcel = $reader->getExcel();
            $sheet = $objExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
            Log::info('highestRow: ' . $highestRow);
            Log::info('highestColumn: ' . $highestColumn);

            //            //  Loop through each row of the worksheet in turn
            //            for ($row = 1; $row <= $highestRow; $row++)
            //            {
            //                //  Read a row of data into an array
            //                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
            //                    NULL, TRUE, FALSE);
            //            }
        });
        Log::info('import end.');

        return redirect('basic/biddinginformations');
    }

    /**
     * export to excel/pdf.
     *
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        // Log::info($request->all());

        $filename = 'BAOJIA';
        // $filename = iconv("UTF-8", "GBK//IGNORE", '中标信息');
        Log::info('export 1');
        Excel::create($filename, function ($excel) use ($request) {
            $excel->sheet('项目明细', function ($sheet) use ($request) {
                $query = Biddinginformationdefinefield::where(function ($query) {
                    $query->where('exceltype', '项目明细')
                        ->orWhere('exceltype', '汇总明细');
                });
                if ($request->has('projecttypes_export') && !empty($request->input('projecttypes_export'))) {
                    $projecttypes = explode(',', $request->input('projecttypes_export'));
                    $query->whereIn('projecttype', $projecttypes);
                }
                $biddinginformationdefinefields = $query->orderBy('sort')->get();
//                $biddinginformationdefinefields = Biddinginformationdefinefield::where('exceltype', '项目明细')->orWhere('exceltype', '汇总明细')->orderBy('sort')->get();
                $data = [];
                array_push($data, '编号');
                array_push($data, '执行成本（动态计算）');
                array_push($data, '总纲耗（动态计算）');
                array_push($data, '开工日期');
                foreach ($biddinginformationdefinefields as $biddinginformationdefinefield)
                {
                    array_push($data, $biddinginformationdefinefield->name);
                }
                $sheet->appendRow($data);
                $rowCol = 2;        // 从第二行开始

                 $query = $this->searchrequest($request);
//                $query = DB::table('biddinginformations')->latest()->select('id', 'number', 'sohead_id')->addSelect(DB::raw("
//                    (SELECT SUM(AMOUNT) FROM [hxcrm2016].[dbo].[vpurchaseorder] A WHERE A.[sohead_id] = biddinginformations.sohead_id)
//                    + hxcrm2016.dbo.getPoheadAmountBy7550(biddinginformations.sohead_id)
//                    + (SELECT sohead_taxamount - sohead_poheadtaxamount FROM [hxcrm2016].[dbo].[tem_taxamountstatistics]
//                        WHERE [hxcrm2016].[dbo].[tem_taxamountstatistics].[sohead_id] = biddinginformations.sohead_id)
//                    - hxcrm2016.dbo.getPoheadTaxAmountBy7550(biddinginformations.sohead_id) purchase_cost,
//                    hxcrm2016.dbo.getWarehouseAmountByorder(biddinginformations.sohead_id)
//                    + hxcrm2016.dbo.getNoWarehouseAmountByorder(biddinginformations.sohead_id)
//                    + (SELECT sohead_taxamount FROM [hxcrm2016].[dbo].[tem_taxamountstatistics]
//                        WHERE [hxcrm2016].[dbo].[tem_taxamountstatistics].[sohead_id] = biddinginformations.sohead_id)
//                    + hxcrm2016.dbo.getNoWarehouseAmountBy7550(biddinginformations.sohead_id)
//                    - hxcrm2016.dbo.getNoWarehouseTaxAmountByorder(biddinginformations.sohead_id)
//                    - hxcrm2016.dbo.getWarehouseTaxAmountByorder(biddinginformations.sohead_id) out_cost,
//                    (SELECT sum([tonnage]) FROM [issuedrawings] WHERE [issuedrawings].[sohead_id] = biddinginformations.sohead_id
//                        AND [status] = '0' AND [issuedrawings].[deleted_at] IS NULL) tonnage,
//                    (select top 1 startDate from hxcrm2016.dbo.vorder where id = biddinginformations.sohead_id) startDate
//                "));
                $query->chunk(100, function ($biddinginformations) use ($sheet, $biddinginformationdefinefields, &$rowCol) {
                    foreach ($biddinginformations as $biddinginformation) {
                        $data = [];
                        $comments = [];
                        array_push($data, $biddinginformation->number);
                        array_push($comments, '');

                        // 增加执行成本和总钢耗
                        $bExist = false;
                        if (isset($biddinginformation->sohead_id) && $biddinginformation->sohead_id > 0)
                        {
                            $sohead = Salesorder_hxold::find($biddinginformation->sohead_id);
                            if (isset($sohead))
                            {
                                $pohead_amount_total = $sohead->poheads->sum('amount');
                                $poheadAmountBy7550 = array_first($sohead->getPoheadAmountBy7550())->poheadAmountBy7550;
                                $sohead_taxamount = isset($sohead->temTaxamountstatistics->sohead_taxamount) ? $sohead->temTaxamountstatistics->sohead_taxamount : 0.0;
                                $sohead_poheadtaxamount = isset($sohead->temTaxamountstatistics->sohead_poheadtaxamount) ? $sohead->temTaxamountstatistics->sohead_poheadtaxamount : 0.0;
                                $sohead_poheadtaxamountby7550 = array_first($sohead->getPoheadTaxAmountBy7550())->poheadTaxAmountBy7550;
                                $totalpurchaseamount = $pohead_amount_total + $poheadAmountBy7550 + $sohead_taxamount - $sohead_poheadtaxamount - $sohead_poheadtaxamountby7550;

                                $warehousecost=array_first($sohead->getwarehouseCost())->warehousecost;
                                $nowarehousecost=array_first($sohead->getnowarehouseCost())->nowarehousecost;
                                $nowarehouseamountby7550=array_first($sohead->getnowarehouseamountby7550())->nowarehouseamountby7550;
                                $nowarehousetaxcost=array_first($sohead->getnowarehousetaxCost())->nowarehousetaxcost;
                                $warehousetaxcost=array_first($sohead->getwarehousetaxCost())->warehousetaxcost;
                                $totalwarehouseamount = $warehousecost  + $nowarehousecost + $sohead_taxamount + $nowarehouseamountby7550 - $nowarehousetaxcost - $warehousetaxcost;
                                array_push($data, "采购成本：" . $totalpurchaseamount . "，出库成本：" . $totalwarehouseamount);
                                array_push($comments, '');

                                $issuedrawing_tonnage = $sohead->issuedrawings()->where('status', 0)->sum('tonnage');
                                array_push($data, $issuedrawing_tonnage);
                                array_push($comments, '');

                                // 开工日期
                                array_push($data, Carbon::parse($sohead->startDate)->toDateString());
                                array_push($comments, '');

                                $bExist = true;
                            }
                        }

                        if (!$bExist) {
                            array_push($data, '');
                            array_push($comments, '');

                            array_push($data, '');
                            array_push($comments, '');

                            array_push($data, '');
                            array_push($comments, '');
                        }

                        foreach ($biddinginformationdefinefields as $biddinginformationdefinefield)
                        {
//                            $biddinginformationitem = $biddinginformation->biddinginformationitems()->where('key', $biddinginformationdefinefield->name)->first();
                            $biddinginformationitem = Biddinginformationitem::where('biddinginformation_id', $biddinginformation->id)->where('key', $biddinginformationdefinefield->name)->first();
                            array_push($data, isset($biddinginformationitem) ? $biddinginformationitem->value : '');
                            array_push($comments, isset($biddinginformationitem) ? $biddinginformationitem->remark : '');
                        }
                        $sheet->appendRow($data);

                        // 添加批注
                        $colIndex = 'A';
                        foreach ($comments as $comment) {
                            if (strlen($comment) > 0)
                                $sheet->getComment($colIndex . $rowCol)->getText()->createTextRun($comment);
                            $colIndex++;
                        }
                        $rowCol++;
                    }
                });
                $freezeCol = config('custom.bidding.freeze_detail_col', 'B');
                $sheet->setFreeze($freezeCol . '2');
            });

            $excel->sheet('汇总表', function ($sheet) use ($request) {
                $query = Biddinginformationdefinefield::where(function ($query) {
                    $query->where('exceltype', '汇总表')
                        ->orWhere('exceltype', '汇总明细');
                });
                if ($request->has('projecttypes_export') && !empty($request->input('projecttypes_export'))) {
                    $projecttypes = explode(',', $request->input('projecttypes_export'));
                    $query->whereIn('projecttype', $projecttypes);
                }
                $biddinginformationdefinefields = $query->orderBy('sort')->get();
//                $biddinginformationdefinefields = Biddinginformationdefinefield::where('exceltype', '汇总表')->orWhere('exceltype', '汇总明细')->orderBy('sort')->get();
                $data = [];
                array_push($data, '编号');
                foreach ($biddinginformationdefinefields as $biddinginformationdefinefield)
                {
                    array_push($data, $biddinginformationdefinefield->name);
                }
                $sheet->appendRow($data);
                $rowCol = 2;        // 从第二行开始
                $query = $this->searchrequest($request);
                $query->chunk(100, function ($biddinginformations) use ($sheet, $biddinginformationdefinefields, &$rowCol) {
                    foreach ($biddinginformations as $biddinginformation) {
                        $data = [];
                        $comments = [];
                        array_push($data, $biddinginformation->number);
                        array_push($comments, '');
                        foreach ($biddinginformationdefinefields as $biddinginformationdefinefield)
                        {
                            $biddinginformationitem = Biddinginformationitem::where('biddinginformation_id', $biddinginformation->id)->where('key', $biddinginformationdefinefield->name)->first();
                            array_push($data, isset($biddinginformationitem) ? $biddinginformationitem->value : '');
                            array_push($comments, isset($biddinginformationitem) ? $biddinginformationitem->remark : '');
                        }
                        $sheet->appendRow($data);

                        // 添加批注
                        $colIndex = 'A';
                        foreach ($comments as $comment) {
                            if (strlen($comment) > 0)
                                $sheet->getComment($colIndex . $rowCol)->getText()->createTextRun($comment);
                            $colIndex++;
                        }
                        $rowCol++;
                    }
                });
                $freezeCol = config('custom.bidding.freeze_summary_col', 'B');
                $sheet->setFreeze($freezeCol . '2');
            });
            Log::info('export 2');

            //            // Set the title
            //            $excel->setTitle('Our new awesome title');
            //
            //            // Chain the setters
            //            $excel->setCreator('Maatwebsite')
            //                ->setCompany('Maatwebsite');
            //
            //            // Call them separately
            //            $excel->setDescription('A demonstration to change the file properties');

        })->store('xlsx', public_path('download/biddinginformations'));

        //        $newfilename = 'export_' . Carbon::now()->format('YmdHis') . '.xlsx';
        //        Log::info($newfilename);
        //        rename(public_path('download/shipment/Shipments.xlsx'), public_path('download/shipment/' . $newfilename));

        //        Log::info(route('basic.biddinginformations.downloadfile', ['filename' => $filename . '.xlsx']));
        return route('basic.biddinginformations.downloadfile', ['filename' => $filename . '.xlsx']);
    }

    // https://www.cnblogs.com/cyclzdblog/p/7670695.html
    public function downloadfile($filename, $newfilename = null)
    {
        //        Log::info('filename: ' . $filename);
        //        $newfilename = substr($filename, 0, strpos($filename, ".")) . Carbon::now()->format('YmdHis') . substr($filename, strpos($filename, "."));
        //        Log::info($newfilename);
        //        rename(public_path('download/shipment/' . $filename), public_path('download/shipment/' . $newfilename));
        //        $file = public_path('download/biddinginformations/' . iconv("GBK//IGNORE","UTF-8", $filename));
        $file = public_path('download/biddinginformations/' . $filename);
        Log::info('file path:' . $file);
        return response()->download($file, $newfilename);
    }

    /**
     * export to excel/pdf.
     * 导出不带数据的Excel。如果有数据填写“已输入”，无数据填写“未输入”
     *
     * @return \Illuminate\Http\Response
     */
    public function export2(Request $request)
    {
        //        Log::info($request->all());

        $filename = 'BAOJIA';
        //        $filename = iconv("UTF-8","GBK//IGNORE", '中标信息');
        Log::info('export 1');



        Excel::create($filename, function ($excel) use ($request) {
            $excel->sheet('项目明细', function ($sheet) use ($request) {
                $query = Biddinginformationdefinefield::where(function ($query) {
                    $query->where('exceltype', '项目明细')
                        ->orWhere('exceltype', '汇总明细');
                });
                if ($request->has('projecttypes_export') && !empty($request->input('projecttypes_export'))) {
                    $projecttypes = explode(',', $request->input('projecttypes_export'));
                    $query->whereIn('projecttype', $projecttypes);
                }
                $biddinginformationdefinefields = $query->orderBy('sort')->get();
                //                $biddinginformationdefinefields = Biddinginformationdefinefield::where('exceltype', '项目明细')->orWhere('exceltype', '汇总明细')->orderBy('sort')->get();
                $data = [];
                array_push($data, '编号');
                array_push($data, '执行成本（动态计算）');
                array_push($data, '总纲耗（动态计算）');
                array_push($data, '开工日期');
                foreach ($biddinginformationdefinefields as $biddinginformationdefinefield) {
                    array_push($data, $biddinginformationdefinefield->name);
                }
                $sheet->appendRow($data);
                $rowCol = 2;        // 从第二行开始


                $query = $this->searchrequest($request);
                $query->chunk(100, function ($biddinginformations) use ($sheet, $biddinginformationdefinefields, &$rowCol) {
                    foreach ($biddinginformations as $biddinginformation) {
                        $data = [];
                        $comments = [];
                        array_push($data, $biddinginformation->number);
                        array_push($comments, '');

                        // 增加执行成本和总钢耗
                        $bExist = false;
                        if (isset($biddinginformation->sohead_id) && $biddinginformation->sohead_id > 0) {
                            $sohead = Salesorder_hxold::find($biddinginformation->sohead_id);
                            if (isset($sohead)) {
                                $pohead_amount_total = $sohead->poheads->sum('amount');
                                $poheadAmountBy7550 = array_first($sohead->getPoheadAmountBy7550())->poheadAmountBy7550;
                                $sohead_taxamount = isset($sohead->temTaxamountstatistics->sohead_taxamount) ? $sohead->temTaxamountstatistics->sohead_taxamount : 0.0;
                                $sohead_poheadtaxamount = isset($sohead->temTaxamountstatistics->sohead_poheadtaxamount) ? $sohead->temTaxamountstatistics->sohead_poheadtaxamount : 0.0;
                                $sohead_poheadtaxamountby7550 = array_first($sohead->getPoheadTaxAmountBy7550())->poheadTaxAmountBy7550;
                                $totalpurchaseamount = $pohead_amount_total + $poheadAmountBy7550 + $sohead_taxamount - $sohead_poheadtaxamount - $sohead_poheadtaxamountby7550;

                                $warehousecost = array_first($sohead->getwarehouseCost())->warehousecost;
                                $nowarehousecost = array_first($sohead->getnowarehouseCost())->nowarehousecost;
                                $nowarehouseamountby7550 = array_first($sohead->getnowarehouseamountby7550())->nowarehouseamountby7550;
                                $nowarehousetaxcost = array_first($sohead->getnowarehousetaxCost())->nowarehousetaxcost;
                                $warehousetaxcost = array_first($sohead->getwarehousetaxCost())->warehousetaxcost;
                                $totalwarehouseamount = $warehousecost  + $nowarehousecost + $sohead_taxamount + $nowarehouseamountby7550 - $nowarehousetaxcost - $warehousetaxcost;
                                array_push($data, "采购成本：" . $totalpurchaseamount . "，出库成本：" . $totalwarehouseamount);
                                array_push($comments, '');

                                $issuedrawing_tonnage = $sohead->issuedrawings()->where('status', 0)->sum('tonnage');
                                array_push($data, $issuedrawing_tonnage);
                                array_push($comments, '');

                                // 开工日期
                                array_push($data, Carbon::parse($sohead->startDate)->toDateString());
                                array_push($comments, '');

                                $bExist = true;
                            }
                        }
                        if (!$bExist) {
                            array_push($data, '');
                            array_push($comments, '');

                            array_push($data, '');
                            array_push($comments, '');

                            array_push($data, '');
                            array_push($comments, '');
                        }

                        foreach ($biddinginformationdefinefields as $biddinginformationdefinefield) {
                            $biddinginformationitem = Biddinginformationitem::where('biddinginformation_id', $biddinginformation->id)->where('key', $biddinginformationdefinefield->name)->first();
                            array_push($data, isset($biddinginformationitem) && !empty($biddinginformationitem->value) ? '已输入' : '未输入');
                            array_push($comments, isset($biddinginformationitem) ? $biddinginformationitem->remark : '');
                        }
                        $sheet->appendRow($data);

                        // 添加批注
                        //                        $colIndex = 'A';
                        //                        foreach ($comments as $comment)
                        //                        {
                        //                            if (strlen($comment) > 0)
                        //                                $sheet->getComment($colIndex . $rowCol)->getText()->createTextRun($comment);
                        //                            $colIndex++;
                        //                        }
                        $rowCol++;
                    }
                });
                $freezeCol = config('custom.bidding.freeze_detail_col', 'B');
                $sheet->setFreeze($freezeCol . '2');
            });

            $excel->sheet('汇总表', function ($sheet) use ($request) {
                $query = Biddinginformationdefinefield::where(function ($query) {
                    $query->where('exceltype', '汇总表')
                        ->orWhere('exceltype', '汇总明细');
                });
                if ($request->has('projecttypes_export') && !empty($request->input('projecttypes_export'))) {
                    $projecttypes = explode(',', $request->input('projecttypes_export'));
                    $query->whereIn('projecttype', $projecttypes);
                }
                $biddinginformationdefinefields = $query->orderBy('sort')->get();
                //                $biddinginformationdefinefields = Biddinginformationdefinefield::where('exceltype', '汇总表')->orWhere('exceltype', '汇总明细')->orderBy('sort')->get();
                $data = [];
                array_push($data, '编号');
                foreach ($biddinginformationdefinefields as $biddinginformationdefinefield) {
                    array_push($data, $biddinginformationdefinefield->name);
                }
                $sheet->appendRow($data);
                $rowCol = 2;        // 从第二行开始
                $query = $this->searchrequest($request);
                $query->chunk(100, function ($biddinginformations) use ($sheet, $biddinginformationdefinefields, &$rowCol) {
                    foreach ($biddinginformations as $biddinginformation) {
                        $data = [];
                        $comments = [];
                        array_push($data, $biddinginformation->number);
                        array_push($comments, '');
                        foreach ($biddinginformationdefinefields as $biddinginformationdefinefield) {
                            $biddinginformationitem = Biddinginformationitem::where('biddinginformation_id', $biddinginformation->id)->where('key', $biddinginformationdefinefield->name)->first();
                            array_push($data, isset($biddinginformationitem) && !empty($biddinginformationitem->value) ? '已输入' : '未输入');
                            array_push($comments, isset($biddinginformationitem) ? $biddinginformationitem->remark : '');
                        }
                        $sheet->appendRow($data);

                        // 添加批注
                        //                        $colIndex = 'A';
                        //                        foreach ($comments as $comment)
                        //                        {
                        //                            if (strlen($comment) > 0)
                        //                                $sheet->getComment($colIndex . $rowCol)->getText()->createTextRun($comment);
                        //                            $colIndex++;
                        //                        }
                        $rowCol++;
                    }
                });
                $freezeCol = config('custom.bidding.freeze_summary_col', 'B');
                $sheet->setFreeze($freezeCol . '2');
            });
            Log::info('export 2');

            //            // Set the title
            //            $excel->setTitle('Our new awesome title');
            //
            //            // Chain the setters
            //            $excel->setCreator('Maatwebsite')
            //                ->setCompany('Maatwebsite');
            //
            //            // Call them separately
            //            $excel->setDescription('A demonstration to change the file properties');

        })->store('xlsx', public_path('download/biddinginformations'));

        return route('basic.biddinginformations.downloadfile', ['filename' => $filename . '.xlsx', 'newfilename' => '投标信息（不带实际数据）.xlsx']);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function clear(Request $request)
    {
        $biddinginformations = $this->searchrequest($request)->get();
        foreach ($biddinginformations as $biddinginformation) {
            //            Log::info($biddinginformation->id);
            $biddinginformation->delete();
        }

        return route('basic.biddinginformations.index');
    }

    public function exportword($id)
    {
        $biddinginformation = Biddinginformation::findOrFail($id);

        $phpWord = new PhpWord();

        $section = $phpWord->createSection();

        $i = 1;
        foreach ($biddinginformation->biddinginformationitems as $biddinginformationitem) {
            $str = $i . '、' . $biddinginformationitem->key . '：' . $biddinginformationitem->value;
            $section->addText($str);
            $i++;
        }

        $writer = IOFactory::createWriter($phpWord);
        $writer->save(public_path('download/biddinginformations/TOUBIAO.docx'));

        return response()->download(public_path('download/biddinginformations/TOUBIAO.docx'));


        // https://www.cnblogs.com/duanyingkui/p/8367411.html
        //        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        //        //设置默认样式
        //        $phpWord->setDefaultFontName('仿宋');//字体
        //        $phpWord->setDefaultFontSize(16);//字号
        //
        //        //添加页面
        //        $section = $phpWord->createSection();
        //
        //        //添加目录
        //        $styleTOC  = ['tabLeader' => \PhpOffice\PhpWord\Style\TOC::TABLEADER_DOT];
        //        $styleFont = ['spaceAfter' => 60, 'name' => 'Tahoma', 'size' => 12];
        //        $section->addTOC($styleFont, $styleTOC);
        //
        //        //默认样式
        //        $section->addText('Hello PHP!');
        //        $section->addTextBreak();//换行符
        //
        //        //指定的样式
        //        $section->addText(
        //            'Hello world!',
        //            [
        //                'name' => '宋体',
        //                'size' => 16,
        //                'bold' => true,
        //            ]
        //        );
        //        $section->addTextBreak(5);//多个换行符
        //
        //        //自定义样式
        //        $myStyle = 'myStyle';
        //        $phpWord->addFontStyle(
        //            $myStyle,
        //            [
        //                'name' => 'Verdana',
        //                'size' => 12,
        //                'color' => '1BFF32',
        //                'bold' => true,
        //                'spaceAfter' => 20,
        //            ]
        //        );
        //        $section->addText('Hello Laravel!', $myStyle);
        //        $section->addText('Hello Vue.js!', $myStyle);
        //        $section->addPageBreak();//分页符
        //
        //        //添加文本资源
        //        $textrun = $section->createTextRun();
        //        $textrun->addText('加粗', ['bold' => true]);
        //        $section->addTextBreak();//换行符
        //        $textrun->addText('倾斜', ['italic' => true]);
        //        $section->addTextBreak();//换行符
        //        $textrun->addText('字体颜色', ['color' => 'AACC00']);
        //
        //        //超链接
        //        $linkStyle = ['color' => '0000FF', 'underline' => \PhpOffice\PhpWord\Style\Font::UNDERLINE_SINGLE];
        //        $phpWord->addLinkStyle('myLinkStyle', $linkStyle);
        //        $section->addLink('http://www.baidu.com', '百度一下', 'myLinkStyle');
        //        $section->addLink('http://www.baidu.com', null, 'myLinkStyle');
        //
        //        //添加图片
        //        $imageStyle = ['width' => 480, 'height' => 640, 'align' => 'center'];
        //        $section->addImage('./img/t1.jpg', $imageStyle);
        //        $section->addImage('./img/t2.jpg',$imageStyle);
        //
        //        //添加标题
        //        $phpWord->addTitleStyle(1, ['bold' => true, 'color' => '1BFF32', 'size' => 38, 'name' => 'Verdana']);
        //        $section->addTitle('标题1', 1);
        //        $section->addTitle('标题2', 1);
        //        $section->addTitle('标题3', 1);
        //
        //        //添加表格
        //        $styleTable = [
        //            'borderColor' => '006699',
        //            'borderSize' => 6,
        //            'cellMargin' => 50,
        //        ];
        //        $styleFirstRow = ['bgColor' => '66BBFF'];//第一行样式
        //        $phpWord->addTableStyle('myTable', $styleTable, $styleFirstRow);
        //
        //        $table = $section->addTable('myTable');
        //        $table->addRow(400);//行高400
        //        $table->addCell(2000)->addText('学号');
        //        $table->addCell(2000)->addText('姓名');
        //        $table->addCell(2000)->addText('专业');
        //        $table->addRow(400);//行高400
        //        $table->addCell(2000)->addText('2015123');
        //        $table->addCell(2000)->addText('小明');
        //        $table->addCell(2000)->addText('计算机科学与技术');
        //        $table->addRow(400);//行高400
        //        $table->addCell(2000)->addText('2016789');
        //        $table->addCell(2000)->addText('小傻');
        //        $table->addCell(2000)->addText('教育学技术');
        //
        //        //页眉与页脚
        //        $header = $section->createHeader();
        //        $footer = $section->createFooter();
        //        $header->addPreserveText('页眉');
        //        $footer->addPreserveText('页脚 - 页数 {PAGE} - {NUMPAGES}.');
        //
        //        //生成的文档为Word2007
        //        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        //        $writer->save('./word/hello.docx');
        //
        //        //将文档保存为ODT文件...
        //        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'ODText');
        //        $writer->save('./word/hello.odt');
        //
        //        //将文档保存为HTML文件...
        //        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
        //        $writer->save('./word/hello.html');
    }

    public function xyexportword($id)
    {
        $biddinginformation = Biddinginformation::findOrFail($id);

        $phpWord = new PhpWord();

        $section = $phpWord->createSection();

        $i = 1;
        foreach ($biddinginformation->biddinginformationeditems as $biddinginformationeditem) {
            $str = $i . '、' . $biddinginformationeditem->key . '：' . $biddinginformationeditem->value;
            $section->addText($str);
            $i++;
        }

        $writer = IOFactory::createWriter($phpWord);
        $writer->save(public_path('download/biddinginformations/TOUBIAO.docx'));

        return response()->download(public_path('download/biddinginformations/TOUBIAO.docx'));
    }

    public function close($id)
    {
        //
        $biddinginformation = Biddinginformation::find($id);
        if (isset($biddinginformation)) {
            $biddinginformation->closed = 1;
            $biddinginformation->save();
        }
        return redirect('basic/biddinginformations');
    }

    /**
     * 取消订单关联
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function cancelsohead($id)
    {
        //
        $biddinginformation = Biddinginformation::find($id);
        if (isset($biddinginformation)) {
            $biddinginformation->sohead_id = null;
            $biddinginformation->save();
        }
        return redirect('basic/biddinginformations');
    }

    public function edittable()
    {
        $request = request();
        $inputs = $request->all();
        $biddinginformations = $this->searchrequest($request)->paginate(15);
        $biddinginformationdefinefields = Biddinginformationdefinefield::orderBy('sort')->pluck('name');
        //        dd(json_encode($biddinginformations->toArray()['data']) );
        return view('basic.biddinginformations.edittable', compact('biddinginformations', 'inputs', 'biddinginformationdefinefields'));
    }

    public function updateedittable(Request $request)
    {
        //        Log::info($request->all());
        //        $inputs = $request->all();
        //        dd($inputs);
        $id = $request->get('pk');
        $biddinginformation = Biddinginformation::findOrFail($id);
        $name = $request->get('name');
        $value = $request->get('value');
        $biddinginformation->$name = $value;
        $biddinginformation->save();
        return 'success';
    }

    public function resetfieldtype(Request $request)
    {
        //
        $inputs = $request->all();
        //        dd($inputs);
        if (!$request->has('biddinginformation_id') || $request->input('biddinginformation_id') <= 0)
            dd('报价信息错误，无法继续。');
        if (!$request->has('projecttypes') || $request->input('projecttypes') == '')
            dd('没有选择项目类型');

        $biddinginformation = Biddinginformation::findOrFail($request->input('biddinginformation_id'));
        if (isset($biddinginformation)) {
            Biddinginformationfieldtype::where('biddinginformation_id', $biddinginformation->id)->delete();
            $projecttypes = explode(',', $request->input('projecttypes'));
            foreach ($projecttypes as $projecttype) {
                Biddinginformationfieldtype::create([
                    'biddinginformation_id'     => $biddinginformation->id,
                    'biddinginformation_fieldtype'  => $projecttype,
                ]);
            }
            $biddinginformationdefinefields = Biddinginformationdefinefield::whereIn('projecttype', $projecttypes)->orderBy('sort')->get();
            foreach ($biddinginformationdefinefields as $biddinginformationdefinefield) {
                $biddinginformationitem = Biddinginformationitem::where('biddinginformation_id', $biddinginformation->id)->where('key', $biddinginformationdefinefield->name)->first();
                if (!isset($biddinginformationitem)) {
                    Biddinginformationitem::create([
                        'biddinginformation_id' => $biddinginformation->id,
                        'key' => $biddinginformationdefinefield->name,
                        'value' => '',
                        'remark' => '',
                        'sort' => $biddinginformationdefinefield->sort,
                        'type' => $biddinginformationdefinefield->type,
                    ]);
                    //                    Log::info($biddinginformationitem->id);
                }
            }
        }

        return redirect('basic/biddinginformations/' . $biddinginformation->id . '/edit');
    }

    public function getbiddinginformationfieldtypes($id)
    {
        //
        $srtn = "";
        $biddinginformation = Biddinginformation::find($id);
        if (isset($biddinginformation)) {
            $biddinginformationfieldtypes = $biddinginformation->biddinginformationfieldtypes->pluck('biddinginformation_fieldtype')->toArray();
            //            dd($biddinginformationfieldtypes);
            //            $srtn = implode(",", $biddinginformationfieldtypes);
            $srtn = $biddinginformationfieldtypes;
        }
        return $srtn;
    }
}
