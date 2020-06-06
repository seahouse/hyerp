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
use Excel, Log;

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
            'name'      => $request->input('name'),
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
        $constructionbidinformation = Constructionbidinformation::findOrFail($id);
//        dd($biddinginformation->biddinginformationitems()->orderBy('id')->get());
        return view('basic.constructionbidinformations.show', compact('constructionbidinformation'));
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
                    $constructionbidinformationitem->value = doubleval($constructionbidinformation_item->value);
                    $constructionbidinformationitem->multiple = doubleval($constructionbidinformation_item->multiple);
//                    $constructionbidinformationitem->value_line3 = doubleval($constructionbidinformation_item->value_line3);
//                    $constructionbidinformationitem->value_line4 = doubleval($constructionbidinformation_item->value_line4);
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

    public function import()
    {
        //
        return view('basic.constructionbidinformations.import');
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
//        Log::info('import start.');
//        Excel::filter('chunk')->load($file->getRealPath())->chunk(250, function($results)
//        {
//            foreach($results as $row)
//            {
//                // do stuff
//            }
//        });
//        return redirect('basic/biddinginformations');

        Excel::load($file->getRealPath(), function ($reader) use ($request) {
//            $biddinginformationdefinefields = Biddinginformationdefinefield::all();

            $objExcel = $reader->getExcel();
            $sheet = $objExcel->getSheet(0);
//            $sheet2 = $objExcel->getSheetByName('汇总表');
            if (isset($sheet))
//            for ($i = 0; $i < $objExcel->getSheetCount(); $i++)
            {
//                $sheet = $objExcel->getSheet($i);
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                $highestColumn++;

                //  Loop through each row of the worksheet in turn
                $keys = [];
                $keys2 = [];
                //  Read a row of data into an array
                $rowData = $sheet->rangeToArray('A' . 1 . ':' . $highestColumn . 1,
                    NULL, TRUE, FALSE);
                // 第一行，关键字
                $keys = $rowData[0];

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
                    $projecttype = '';
                    for ($row = 5; $row <= $highestRow; $row++)
                    {
                        Log::info($row);
                        $input = [];
                        $index = 0;

                        $input['constructionbidinformation_id'] = $constructionbidinformation->id;
                        $input['seq'] = $sheet->getCell('A' . $row)->getValue();
                        $input['key'] = $sheet->getCell('B' . $row)->getValue();
                        $input['purchaser'] = $sheet->getCell('C' . $row)->getValue();
                        $input['specification_technicalrequirements'] = $sheet->getCell('D' . $row)->getValue();
                        $input['value'] = $sheet->getCell('E' . $row)->getValue();
                        $input['multiple'] = $sheet->getCell('F' . $row)->getValue();
                        $input['unit'] = $sheet->getCell('G' . $row)->getValue();
                        $input['remark'] = $sheet->getCell('H' . $row)->getValue();
//                    for ($colIndex = 'A'; $colIndex != $highestColumn; $colIndex++)
//                    {
//                        // 组装单元格标识  A1  A2
//                        $addr = $colIndex . $row;
//                        // 获取单元格内容
//                        $cell = $sheet->getCell($addr)->getFormattedValue();        // 日期格式，可以用这个函数获取到期望的格式
//                        //富文本转换字符串
//                        if ($cell instanceof PHPExcel_RichText) {
//                            $cell = $cell->__toString();
//                        }
//                    }
//                    dd($input);

                        if (isset($input['purchaser']) && isset($input['key']))
                        {
                            $input['projecttype'] = $projecttype;

                            $sort = 0;
                            $constructionbidinformationfield = Constructionbidinformationfield::where('name', $input['key'])->where('projecttype', $projecttype)->first();
                            if (isset($constructionbidinformationfield))
                                $sort = $constructionbidinformationfield->sort;
                            $input['sort'] = $sort;

//                            dd($input);
                            Constructionbidinformationitem::create($input);
//                            $constructionbidinformationfields = Constructionbidinformationfield::whereIn('projecttype', $projecttype)->orderBy('sort')->get();
//                            foreach ($constructionbidinformationfields as $constructionbidinformationfield)
//                            {
//
//                            }

                        }
                        elseif (!isset($input['purchaser']) && isset($input['key']) && isset($input['seq']))
                        {
                            $projecttype = $input['key'];
//                            dd($projecttype);

                            $constructionbidinformationfieldtype = Constructionbidinformationfieldtype::where('constructionbidinformation_id', $constructionbidinformation->id)->where('constructionbidinformation_fieldtype', $projecttype)->first();
                            if (!isset($constructionbidinformationfieldtype))
                            {
                                Constructionbidinformationfieldtype::create([
                                    'constructionbidinformation_id'     => $constructionbidinformation->id,
                                    'constructionbidinformation_fieldtype'  => $projecttype,
                                ]);
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

        return redirect('basic/constructionbidinformations');
    }

    public function edittable($id)
    {
        $constructionbidinformation = Constructionbidinformation::findOrFail($id);
//        $biddinginformationdefinefields = Biddinginformationdefinefield::orderBy('sort')->pluck('name');
//        dd(json_encode($biddinginformations->toArray()['data']) );
        return view('basic.constructionbidinformations.edittable', compact('constructionbidinformation'));
    }

    public function exportexcel($id)
    {
        Excel::load('exceltemplate/Constructionbidinformation.xlsx', function ($reader) use ($id) {
            $objExcel = $reader->getExcel();
            $sheet = $objExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();

            $constructionbidinformation = Constructionbidinformation::findOrFail($id);
            if (isset($constructionbidinformation))
            {
                $upperseqs = ['一', '二', '三', '四', '五', '六', '七', '八', '九', '十', '十一', '十二', '十三', '十四', '十五', '十六', '十七', '十八', '十九', '二十'];
                $projecttype = '';
                $row = 5;
                $seq = 1;
                $upperseq = 0;
                foreach ($constructionbidinformation->constructionbidinformationitems as $constructionbidinformationitem)
                {
                    if ($projecttype <> $constructionbidinformationitem->projecttype)
                    {
                        if (count($upperseqs) > $upperseq)
                        {
                            $sheet->setCellValue('A' . $row, $upperseqs[$upperseq]);
                            $upperseq++;
                        }
                        $sheet->setCellValue('B' . $row, $constructionbidinformationitem->projecttype);
                        $projecttype = $constructionbidinformationitem->projecttype;
                        $seq = 1;
                        $row++;

                        $sheet->setCellValue('A' . $row, $seq);
                        $sheet->setCellValue('B' . $row, $constructionbidinformationitem->key);
                        $sheet->setCellValue('C' . $row, $constructionbidinformationitem->purchaser);
                        $sheet->setCellValue('D' . $row, $constructionbidinformationitem->specification_technicalrequirements);
                        $sheet->setCellValue('E' . $row, $constructionbidinformationitem->value);
                        $sheet->setCellValue('F' . $row, $constructionbidinformationitem->multiple);
                        $sheet->setCellValue('G' . $row, $constructionbidinformationitem->unit);
                        $sheet->setCellValue('H' . $row, $constructionbidinformationitem->remark);
                        $seq++;
                        $row++;
                    }
                    else
                    {
                        $sheet->setCellValue('A' . $row, $seq);
                        $sheet->setCellValue('B' . $row, $constructionbidinformationitem->key);
                        $sheet->setCellValue('C' . $row, $constructionbidinformationitem->purchaser);
                        $sheet->setCellValue('D' . $row, $constructionbidinformationitem->specification_technicalrequirements);
                        $sheet->setCellValue('E' . $row, $constructionbidinformationitem->value);
                        $sheet->setCellValue('F' . $row, $constructionbidinformationitem->multiple);
                        $sheet->setCellValue('G' . $row, $constructionbidinformationitem->unit);
                        $sheet->setCellValue('H' . $row, $constructionbidinformationitem->remark);
                        $seq++;
                        $row++;
                    }
                }

                $styleThinBlackBorderOutline = array(
                    'borders' => array(
                        'allborders' => array( //设置全部边框
                            'style' => \PHPExcel_Style_Border::BORDER_THIN //粗的是thick
                        ),

                    ),
                );
                $sheet->getStyle( 'A5' . ':H' . ($row-1))->applyFromArray($styleThinBlackBorderOutline);

//                $data_interchange_datetime = Carbon::parse($constructionbidinformation->data_interchange_datetime);
//                $sheet->setCellValue('D5', $data_interchange_datetime->format('M d, Y'));
//                $sheet->setCellValue('G5', $data_interchange_datetime->format('M d, Y'));
//                $sheet->setCellValue('D6', $constructionbidinformation->salesman_name);
//                $sheet->setCellValue('G6', $constructionbidinformation->purchase_order_number);
//                $sheet->setCellValue('G10', $constructionbidinformation->supplier_name);
//
//                $totalprice = 0.0;
//                $totalquantity = 0;
//                $detail_startrow = 34;
//                $detail_row = $detail_startrow;
//                $currentitemcount = 1;
//                foreach ($constructionbidinformation->poitemcs as $poitemc)
//                {
//                    if ($currentitemcount > 1)
//                    {
//                        $sheet->insertNewRowBefore($detail_row, 14);
//
//                        $sheet->setCellValue('B' . $detail_row, $sheet->getCell('B'.($detail_row-14))->getValue());
//                        $sheet->setCellValue('E' . $detail_row, $sheet->getCell('E'.($detail_row-14))->getValue());
//                        $sheet->setCellValue('B' . ($detail_row+1), $sheet->getCell('B'.($detail_row+1-14))->getValue());
//                        $sheet->setCellValue('D' . ($detail_row+1), $sheet->getCell('D'.($detail_row+1-14))->getValue());
//                        $sheet->setCellValue('B' . ($detail_row+2), $sheet->getCell('B'.($detail_row+2-14))->getValue());
//                        $sheet->setCellValue('B' . ($detail_row+3), $sheet->getCell('B'.($detail_row+3-14))->getValue());
//                        $sheet->setCellValue('C' . ($detail_row+3), $sheet->getCell('C'.($detail_row+3-14))->getValue());
//                        $sheet->setCellValue('B' . ($detail_row+4), $sheet->getCell('B'.($detail_row+4-14))->getValue());
//                        $sheet->setCellValue('E' . ($detail_row+4), $sheet->getCell('E'.($detail_row+4-14))->getValue());
//                        $sheet->setCellValue('B' . ($detail_row+5), $sheet->getCell('B'.($detail_row+5-14))->getValue());
//                        $sheet->setCellValue('E' . ($detail_row+5), $sheet->getCell('E'.($detail_row+5-14))->getValue());
//                        $sheet->setCellValue('B' . ($detail_row+6), $sheet->getCell('B'.($detail_row+6-14))->getValue());
//                        $sheet->setCellValue('B' . ($detail_row+7), $sheet->getCell('B'.($detail_row+7-14))->getValue());
//                        $sheet->setCellValue('B' . ($detail_row+8), $sheet->getCell('B'.($detail_row+8-14))->getValue());
//                        $sheet->setCellValue('B' . ($detail_row+9), $sheet->getCell('B'.($detail_row+9-14))->getValue());
//                        $sheet->setCellValue('B' . ($detail_row+10), $sheet->getCell('B'.($detail_row+10-14))->getValue());
//                        $sheet->setCellValue('E' . ($detail_row+11), $sheet->getCell('E'.($detail_row+11-14))->getValue());
//                    }
//
//                    $totalprice += $poitemc->quantity * $poitemc->unit_price;
//                    $totalquantity += $poitemc->quantity;
//
//                    $sheet->setCellValue('A' . $detail_row, $poitemc->fabric_sequence_no);
//                    $sheet->setCellValue('D' . $detail_row, $poitemc->material_code);
//                    $fabric_description = trim($poitemc->fabric_description);
//                    $sheet->setCellValue('D' . ($detail_row+2), substr($fabric_description, 0, strpos($fabric_description, "::")));
//                    $sheet->setCellValue('D' . ($detail_row+5), $poitemc->construction);
//                    $sheet->setCellValue('G' . ($detail_row+2), substr($fabric_description, strpos($fabric_description, "::") + 2, strrpos($fabric_description, "::") - strpos($fabric_description, "::") - 2));
//                    $sheet->setCellValue('D' . ($detail_row+6), $poitemc->yarn_count);
//                    $sheet->setCellValue('D' . ($detail_row+7), $poitemc->fabric_width);
//                    $sheet->setCellValue('G' . ($detail_row+11), $poitemc->unit_price);
//
//                    $detail_row += 14;
//                    $currentitemcount += 1;
//                }
//                $sheet->setCellValue('G29', 'USD ' . $totalprice);
//                $sheet->setCellValue('G30', 'USD ' . $totalprice);
//
//                $detail_startrow = 53 + ($currentitemcount-2) * 14;
//                $detail_row = $detail_startrow;
//                $currentitemcount = 1;
//                foreach ($constructionbidinformation->poitemcs as $poitemc)
//                {
//                    if ($currentitemcount > 1)
//                    {
//                        $sheet->insertNewRowBefore($detail_row, 1);
//                    }
//
//                    $sheet->setCellValue('A' . $detail_row, $poitemc->fabric_sequence_no);
//                    Log::info('D' . $detail_row . ':' . $poitemc->color_desc1);
//                    $sheet->setCellValue('D' . $detail_row, $poitemc->color_desc1);
//                    $sheet->setCellValue('E' . $detail_row, $poitemc->quantity);
//                    $shipment_date = Carbon::parse($poitemc->shipment_date);
//                    $sheet->setCellValue('G' . $detail_row, $shipment_date->format('M d, Y'));
//
//                    $detail_row += 1;
//                    $currentitemcount += 1;
//                }
//                $currentrow = $detail_startrow + 2 + ($currentitemcount-2) * 1;
//                Log::info('E' . $currentrow . ', totalquantity:' . $totalquantity);
//                $sheet->setCellValue('E' . $currentrow, $totalquantity);
//                $sheet->setCellValue('G' . $currentrow, 'Line amount USD ' . $totalprice);
            }


        })->export('xlsx');

//        $biddinginformation = Biddinginformation::findOrFail($id);
//
//        $phpWord = new PhpWord();
//
//        $section = $phpWord->createSection();
//
//        $i = 1;
//        foreach ($biddinginformation->biddinginformationitems as $biddinginformationitem)
//        {
//            $str = $i . '、' . $biddinginformationitem->key . '：' . $biddinginformationitem->value;
//            $section->addText($str);
//            $i++;
//        }
//
//        $writer = IOFactory::createWriter($phpWord);
//        $writer->save(public_path('download/biddinginformations/TOUBIAO.docx'));
//
//        return response()->download(public_path('download/biddinginformations/TOUBIAO.docx'));
    }
}
