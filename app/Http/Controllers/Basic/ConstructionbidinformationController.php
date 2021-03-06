<?php

namespace App\Http\Controllers\Basic;

use App\Models\Basic\Constructionbidinformation;
use App\Models\Basic\Constructionbidinformationfield;
use App\Models\Basic\Constructionbidinformationfieldtype;
use App\Models\Basic\Constructionbidinformationitem;
use App\Models\Purchase\Purchaseorder_hxold;
use App\Models\Sales\Salesorder_hxold;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use PHPExcel_Cell_DataType;

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
        if (isset($constructionbidinformation)) {
            $projecttypes = explode(',', $request->input('projecttypes'));
            foreach ($projecttypes as $projecttype) {
                Constructionbidinformationfieldtype::create([
                    'constructionbidinformation_id'     => $constructionbidinformation->id,
                    'constructionbidinformation_fieldtype'  => $projecttype,
                ]);
            }
            $constructionbidinformationfields = Constructionbidinformationfield::whereIn('projecttype', $projecttypes)->orderBy('sort')->get();
            foreach ($constructionbidinformationfields as $constructionbidinformationfield) {
                Constructionbidinformationitem::create([
                    'constructionbidinformation_id' => $constructionbidinformation->id,
                    'key' => $constructionbidinformationfield->name,
                    'sort' => $constructionbidinformationfield->sort,
                    'projecttype' => $constructionbidinformationfield->projecttype,
                    'multiple' => 1,
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
        $constructionbidinformation->update($inputs);
        // dd($inputs);
        $remark_suffix = '_remark';
        if (isset($constructionbidinformation)) {
            $constructionbidinformation_items = json_decode($inputs['items_string']);
            foreach ($constructionbidinformation_items as $constructionbidinformation_item) {
                $constructionbidinformationitem = Constructionbidinformationitem::find($constructionbidinformation_item->constructionbidinformationitem_id);
                if (isset($constructionbidinformationitem)) {
                    //                    $item_array = json_decode(json_encode($issuedrawingcabinet_item), true);
                    //                    $item_array['issuedrawing_id'] = $issuedrawing->id;
                    //                    $issuedrawingcabinet = Issuedrawingcabinet::create($item_array);
                    $constructionbidinformationitem->purchaser = $constructionbidinformation_item->purchaser;
                    $constructionbidinformationitem->specification_technicalrequirements = $constructionbidinformation_item->specification_technicalrequirements;
                    $constructionbidinformationitem->value = doubleval($constructionbidinformation_item->value);
                    $constructionbidinformationitem->multiple = doubleval($constructionbidinformation_item->multiple);
                    $constructionbidinformationitem->unit = $constructionbidinformation_item->unit;
                    // $constructionbidinformationitem->remark = $constructionbidinformation_item->remark;
                    $constructionbidinformationitem->material_fee = empty($constructionbidinformation_item->material_fee) ? 0 : $constructionbidinformation_item->material_fee;
                    $constructionbidinformationitem->install_fee = empty($constructionbidinformation_item->install_fee) ?: $constructionbidinformation_item->install_fee;
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
                $rowData = $sheet->rangeToArray(
                    'A' . 1 . ':' . $highestColumn . 1,
                    NULL,
                    TRUE,
                    FALSE
                );
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
                if (isset($constructionbidinformation)) {
                    $projecttype = '';
                    for ($row = 5; $row <= $highestRow; $row++) {
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

                        if (isset($input['purchaser']) && isset($input['key'])) {
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

                        } elseif (!isset($input['purchaser']) && isset($input['key']) && isset($input['seq'])) {
                            $projecttype = $input['key'];
                            //                            dd($projecttype);

                            $constructionbidinformationfieldtype = Constructionbidinformationfieldtype::where('constructionbidinformation_id', $constructionbidinformation->id)->where('constructionbidinformation_fieldtype', $projecttype)->first();
                            if (!isset($constructionbidinformationfieldtype)) {
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
            if (isset($constructionbidinformation)) {
                $sheet->setCellValue('A3', '       项目：' . $constructionbidinformation->name);

                $upperseqs = ['一', '二', '三', '四', '五', '六', '七', '八', '九', '十', '十一', '十二', '十三', '十四', '十五', '十六', '十七', '十八', '十九', '二十'];
                $projecttype = '';
                $row = 5;
                $seq = 1;
                $upperseq = 0;
                foreach ($constructionbidinformation->constructionbidinformationitems as $constructionbidinformationitem) {
                    if ($projecttype <> $constructionbidinformationitem->projecttype) {
                        if (count($upperseqs) > $upperseq) {
                            $sheet->setCellValue('A' . $row, $upperseqs[$upperseq]);
                            $upperseq++;
                        }
                        $sheet->setCellValue('B' . $row, $constructionbidinformationitem->projecttype);
                        $projecttype = $constructionbidinformationitem->projecttype;
                        $seq = 1;
                        $row++;
                    }

                    $sheet->setCellValue('A' . $row, $seq);
                    $sheet->setCellValue('B' . $row, $constructionbidinformationitem->key);
                    $sheet->setCellValue('C' . $row, $constructionbidinformationitem->purchaser);
                    $sheet->setCellValue('D' . $row, $constructionbidinformationitem->specification_technicalrequirements);
                    $sheet->setCellValue('E' . $row, $constructionbidinformationitem->value);
                    $sheet->setCellValue('F' . $row, $constructionbidinformationitem->multiple);
                    //                        $sheet->setCellValue('G' . $row, $constructionbidinformationitem->unit);
                    // $sheet->setCellValue('H' . $row, $constructionbidinformationitem->remark);
                    $sheet->setCellValueExplicit('H' . $row, "=E{$row} * F{$row}", PHPExcel_Cell_DataType::TYPE_FORMULA);
                    $unitprice = 0.0;
                    $unit = '';
                    $constructionbidinformationfield = Constructionbidinformationfield::where('name', $constructionbidinformationitem->key)->where('projecttype', $projecttype)->first();
                    if (isset($constructionbidinformationfield)) {
                        switch ($constructionbidinformationitem->purchaser) {
                            case '华星东方':
                                $unitprice = $constructionbidinformationfield->unitprice;
                                break;
                            case '投标人':
                                $unitprice = $constructionbidinformationfield->unitprice_bidder;
                                break;
                            default:
                                break;
                        }

                        $unit = $constructionbidinformationfield->unit;
                    }
                    $sheet->setCellValue('I' . $row, $unitprice);
                    $sheet->setCellValue('J' . $row, $constructionbidinformationitem->material_fee);
                    $sheet->setCellValue('K' . $row, $constructionbidinformationitem->install_fee);
                    $sheet->setCellValue('L' . $row, $unitprice * $constructionbidinformationitem->value * $constructionbidinformationitem->multiple);
                    $sheet->setCellValue('G' . $row, $unit);
                    // 数字单元格，左对齐
                    $sheet->getStyle('E' . $row . ':F' . $row)->getAlignment()->setHorizontal('left');                // PHPExcel_Style_Alignment::HORIZONTAL_LEFT
                    $sheet->getStyle('I' . $row . ':L' . $row)->getAlignment()->setHorizontal('left');                // PHPExcel_Style_Alignment::HORIZONTAL_LEFT
                    $seq++;
                    $row++;
                }

                $styleThinBlackBorderOutline = array(
                    'borders' => array(
                        'allborders' => array( //设置全部边框
                            'style' => \PHPExcel_Style_Border::BORDER_THIN //粗的是thick
                        ),

                    ),
                );
                $sheet->getStyle('A5' . ':L' . ($row - 1))->applyFromArray($styleThinBlackBorderOutline);

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

    public function updatesaleorderid(Request $request)
    {
        //
        $input = $request->all();
        //        log::info($input);
        $id = $input['informationid'];
        $constructionbidinformation = Constructionbidinformation::findOrFail($id);
        $retcode = $constructionbidinformation->update(['sohead_id' => $input['soheadid']]);
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
        return response()->json($data);
    }

    /**
     * export to excel/pdf.
     *
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        //        Log::info($request->all());
        $filename = 'Shigongbiao';
        //        $filename = iconv("UTF-8","GBK//IGNORE", '中标信息');
        Log::info('export 1');
        Excel::create($filename, function ($excel) use ($request) {
            $excel->sheet('Sheet1', function ($sheet) use ($request) {
                $query = Constructionbidinformationfield::orderBy('sort');
                //                $query = Biddinginformationdefinefield::where(function ($query) {
                //                    $query->where('exceltype', '项目明细')
                //                        ->orWhere('exceltype', '汇总明细');
                //                });
                //                if ($request->has('projecttypes_export') && !empty($request->input('projecttypes_export')))
                //                {
                //                    $projecttypes = explode(',', $request->input('projecttypes_export'));
                //                    $query->whereIn('projecttype', $projecttypes);
                //                }
                $constructionbidinformationfields = $query->get();
                $data = [];
                array_push($data, '编号');
                array_push($data, '项目名称');
                array_push($data, '对应订单');
                array_push($data, '对应项目');
                array_push($data, '吨位');
                array_push($data, '工艺');
                array_push($data, '执行成本（动态计算）');
                array_push($data, '总纲耗（动态计算）');
                array_push($data, '开工日期');
                array_push($data, '实际采购金额');
                array_push($data, '吸收塔');
                array_push($data, '面积');
                array_push($data, '制浆仓');
                array_push($data, '干粉仓');
                array_push($data, '活性炭');
                array_push($data, '灰库');
                array_push($data, '水泥仓');
                array_push($data, '氨水罐');
                array_push($data, '华星总吨位');
                array_push($data, '投标人总吨位');
                array_push($data, '总吨位');
                array_push($data, '总面积');
                array_push($data, '施工总价');
                foreach ($constructionbidinformationfields as $constructionbidinformationfield) {
                    array_push($data, $constructionbidinformationfield->name);
                }
                $sheet->appendRow($data);
                $rowCol = 2;        // 从第二行开始

                $query = $this->searchrequest($request);
                $query->chunk(100, function ($constructionbidinformations) use ($sheet, $constructionbidinformationfields, &$rowCol) {
                    foreach ($constructionbidinformations as $constructionbidinformation) {
                        $data = [];
                        $comments = [];
                        array_push($data, $constructionbidinformation->number);
                        array_push($data, $constructionbidinformation->name);
                        array_push($comments, '');

                        // 增加执行成本和总钢耗
                        $bExist = false;
                        if (isset($constructionbidinformation->sohead_id) && $constructionbidinformation->sohead_id > 0) {
                            $sohead = Salesorder_hxold::find($constructionbidinformation->sohead_id);
                            if (isset($sohead)) {
                                array_push($data, $sohead->projectjc);
                                array_push($data, isset($sohead->project) ? $sohead->project->name : '');
                                array_push($data, $sohead->boilerduty);

                                $biddinginformation = $sohead->biddinginformation;
                                if (isset($biddinginformation)) {
                                    if (null != $biddinginformation->biddinginformationitems->where('key', '工艺')->first())
                                        array_push($data, $biddinginformation->biddinginformationitems->where('key', '工艺')->first()->value);
                                    else
                                        array_push($data, '');
                                } else
                                    array_push($data, '');

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

                                // 实际采购金额
                                $amount_pohead = Purchaseorder_hxold::where('sohead_id', $sohead->id)->where(function ($query) {
                                    $query->where('productname', 'like', '%钢结构安装%')
                                        ->orWhere('productname', 'like', '%钢结构制作%');
                                })->sum('amount');
                                Log::info($constructionbidinformation->number . ' ' . $amount_pohead);
                                array_push($data, $amount_pohead);

                                if (isset($biddinginformation)) {
                                    if (null != $biddinginformation->biddinginformationitems->where('key', '吸收塔（塔型Niro-Seghers-KS；各20t）')->first())
                                        array_push($data, $biddinginformation->biddinginformationitems->where('key', '吸收塔（塔型Niro-Seghers-KS；各20t）')->first()->value);
                                    else
                                        array_push($data, '');

                                    if (null != $biddinginformation->biddinginformationitems->where('key', '面积')->first())
                                        array_push($data, $biddinginformation->biddinginformationitems->where('key', '面积')->first()->value);
                                    else
                                        array_push($data, '');

                                    if (null != $biddinginformation->biddinginformationitems->where('key', '制浆仓')->first())
                                        array_push($data, $biddinginformation->biddinginformationitems->where('key', '制浆仓')->first()->value);
                                    else
                                        array_push($data, '');

                                    if (null != $biddinginformation->biddinginformationitems->where('key', '干粉仓')->first())
                                        array_push($data, $biddinginformation->biddinginformationitems->where('key', '干粉仓')->first()->value);
                                    else
                                        array_push($data, '');

                                    if (null != $biddinginformation->biddinginformationitems->where('key', '活性炭')->first())
                                        array_push($data, $biddinginformation->biddinginformationitems->where('key', '活性炭')->first()->value);
                                    else
                                        array_push($data, '');

                                    if (null != $biddinginformation->biddinginformationitems->where('key', '灰库')->first())
                                        array_push($data, $biddinginformation->biddinginformationitems->where('key', '灰库')->first()->value);
                                    else
                                        array_push($data, '');

                                    if (null != $biddinginformation->biddinginformationitems->where('key', '水泥仓')->first())
                                        array_push($data, $biddinginformation->biddinginformationitems->where('key', '水泥仓')->first()->value);
                                    else
                                        array_push($data, '');

                                    if (null != $biddinginformation->biddinginformationitems->where('key', '氨水罐')->first())
                                        array_push($data, $biddinginformation->biddinginformationitems->where('key', '氨水罐')->first()->value);
                                    else
                                        array_push($data, '');
                                } else {
                                    array_push($data, '');
                                    array_push($data, '');
                                    array_push($data, '');
                                    array_push($data, '');
                                    array_push($data, '');
                                    array_push($data, '');
                                    array_push($data, '');
                                    array_push($data, '');
                                }

                                $bExist = true;
                            }
                        }
                        if (!$bExist) {
                            array_push($data, '');
                            array_push($data, '');
                            array_push($data, '');
                            array_push($data, '');

                            array_push($data, '');
                            array_push($comments, '');

                            array_push($data, '');
                            array_push($comments, '');

                            array_push($data, '');
                            array_push($comments, '');
                            array_push($data, '');

                            array_push($data, '');
                            array_push($data, '');
                            array_push($data, '');
                            array_push($data, '');
                            array_push($data, '');
                            array_push($data, '');
                            array_push($data, '');
                            array_push($data, '');
                        }

                        $huaxingtonnagetotal = 0.0;
                        $toubiaotonnagetotal = 0.0;
                        $tonnagetotal = 0.0;
                        $areatotal = 0.0;
                        $amounttotal = 0.0;
                        foreach ($constructionbidinformationfields as $constructionbidinformationfield) {
                            //                            $constructionbidinformationitem = $constructionbidinformation->biddinginformationitems()->where('key', $biddinginformationdefinefield->name)->first();
                            $constructionbidinformationitem = Constructionbidinformationitem::where('constructionbidinformation_id', $constructionbidinformation->id)->where('key', $constructionbidinformationfield->name)->where('projecttype', $constructionbidinformationfield->projecttype)->first();
                            array_push($data, isset($constructionbidinformationitem) ? $constructionbidinformationitem->value : '');
                            //                            array_push($comments, isset($constructionbidinformationitem) ? $constructionbidinformationitem->remark : '');

                            if (isset($constructionbidinformationitem)) {
                                if ($constructionbidinformationfield->unit == '吨') {
                                    if ($constructionbidinformationitem->purchaser == '华星东方')
                                        $huaxingtonnagetotal += $constructionbidinformationitem->value * $constructionbidinformationitem->multiple;
                                    elseif ($constructionbidinformationitem->purchaser == '投标人')
                                        $toubiaotonnagetotal += $constructionbidinformationitem->value * $constructionbidinformationitem->multiple;
                                    $tonnagetotal += $constructionbidinformationitem->value * $constructionbidinformationitem->multiple;
                                } elseif ($constructionbidinformationfield->unit == '平方米')
                                    $areatotal += $constructionbidinformationitem->value;

                                $unitprice = 0.0;
                                if ($constructionbidinformationitem->purchaser == '华星东方')
                                    $unitprice = $constructionbidinformationfield->unitprice;
                                elseif ($constructionbidinformationitem->purchaser == '投标人')
                                    $unitprice = $constructionbidinformationfield->unitprice_bidder;
                                $amounttotal += $unitprice * $constructionbidinformationitem->value * $constructionbidinformationitem->multiple;
                            }
                        }
                        array_splice($data, 18, 0, $amounttotal);  // 在第17个位置插入
                        array_splice($data, 18, 0, $tonnagetotal);
                        array_splice($data, 18, 0, $areatotal);
                        array_splice($data, 18, 0, $toubiaotonnagetotal);
                        array_splice($data, 18, 0, $huaxingtonnagetotal);
                        $sheet->appendRow($data);

                        $rowCol++;
                    }
                });
                $freezeCol = config('custom.bidding.freeze_detail_col', 'B');
                $sheet->setFreeze($freezeCol . '2');
            });

            //            $excel->sheet('汇总表', function($sheet) use ($request) {
            //                $query = Biddinginformationdefinefield::where(function ($query) {
            //                    $query->where('exceltype', '汇总表')
            //                        ->orWhere('exceltype', '汇总明细');
            //                });
            //                if ($request->has('projecttypes_export') && !empty($request->input('projecttypes_export')))
            //                {
            //                    $projecttypes = explode(',', $request->input('projecttypes_export'));
            //                    $query->whereIn('projecttype', $projecttypes);
            //                }
            //                $biddinginformationdefinefields = $query->orderBy('sort')->get();
            //                $data = [];
            //                array_push($data, '编号');
            //                foreach ($biddinginformationdefinefields as $biddinginformationdefinefield)
            //                {
            //                    array_push($data, $biddinginformationdefinefield->name);
            //                }
            //                $sheet->appendRow($data);
            //                $rowCol = 2;        // 从第二行开始
            //                $query = $this->searchrequest($request);
            //                $query->chunk(100, function ($biddinginformations) use ($sheet, $biddinginformationdefinefields, &$rowCol) {
            //                    foreach ($biddinginformations as $biddinginformation)
            //                    {
            //                        $data = [];
            //                        $comments = [];
            //                        array_push($data, $biddinginformation->number);
            //                        array_push($comments, '');
            //                        foreach ($biddinginformationdefinefields as $biddinginformationdefinefield)
            //                        {
            //                            $biddinginformationitem = Biddinginformationitem::where('biddinginformation_id', $biddinginformation->id)->where('key', $biddinginformationdefinefield->name)->first();
            //                            array_push($data, isset($biddinginformationitem) ? $biddinginformationitem->value : '');
            //                            array_push($comments, isset($biddinginformationitem) ? $biddinginformationitem->remark : '');
            //                        }
            //                        $sheet->appendRow($data);
            //
            //                        // 添加批注
            //                        $colIndex = 'A';
            //                        foreach ($comments as $comment)
            //                        {
            //                            if (strlen($comment) > 0)
            //                                $sheet->getComment($colIndex . $rowCol)->getText()->createTextRun($comment);
            //                            $colIndex++;
            //                        }
            //                        $rowCol++;
            //                    }
            //                });
            //                $freezeCol = config('custom.bidding.freeze_summary_col', 'B');
            //                $sheet->setFreeze($freezeCol . '2');
            //            });
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

        })->store('xlsx', public_path('download/constructionbidinformations'));

        //        $newfilename = 'export_' . Carbon::now()->format('YmdHis') . '.xlsx';
        //        Log::info($newfilename);
        //        rename(public_path('download/shipment/Shipments.xlsx'), public_path('download/shipment/' . $newfilename));

        //        Log::info(route('basic.biddinginformations.downloadfile', ['filename' => $filename . '.xlsx']));
        return route('basic.constructionbidinformations.downloadfile', ['filename' => $filename . '.xlsx']);
    }

    // https://www.cnblogs.com/cyclzdblog/p/7670695.html
    public function downloadfile($filename)
    {
        //        Log::info('filename: ' . $filename);
        //        $newfilename = substr($filename, 0, strpos($filename, ".")) . Carbon::now()->format('YmdHis') . substr($filename, strpos($filename, "."));
        //        Log::info($newfilename);
        //        rename(public_path('download/shipment/' . $filename), public_path('download/shipment/' . $newfilename));
        //        $file = public_path('download/biddinginformations/' . iconv("GBK//IGNORE","UTF-8", $filename));
        $file = public_path('download/constructionbidinformations/' . $filename);
        Log::info('file path:' . $file);
        return response()->download($file);
    }
}
