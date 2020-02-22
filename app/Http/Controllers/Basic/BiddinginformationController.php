<?php

namespace App\Http\Controllers\Basic;

use App\Http\Controllers\HelperController;
use App\Models\Basic\Biddinginformation;
use App\Models\Basic\Biddinginformationdefinefield;
use App\Models\Basic\Biddinginformationitem;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Excel, Log;

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
        $biddinginformationdefinefields = Biddinginformationdefinefield::all();
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
        $biddinginformation = Biddinginformation::create([]);
        if (isset($biddinginformation))
        {
            $sort = 0;
            $type = 0;
            $remark_suffix = '_remark';
            foreach ($inputs as $key => $value)
            {
                if ($key != '_token')
                {
                    $biddinginformationdefinefield = Biddinginformationdefinefield::where('name', $key)->first();
                    if (isset($biddinginformationdefinefield))
                    {
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
//        $biddinginformation = Biddinginformation::findOrFail($id);
//        $biddinginformation->update($request->all());
        $inputs = $request->all();
//        dd($inputs);
        $remark_suffix = '_remark';
        foreach ($inputs as $key => $value)
        {
            if (!(substr($key, -strlen($remark_suffix)) === $remark_suffix))
            {
                $biddinginformationitem = Biddinginformationitem::where('biddinginformation_id', $id)->where('key', $key)->first();
                if (isset($biddinginformationitem))
                {
//                dd($biddinginformationitem);
                    $remark = isset($inputs[$key . $remark_suffix]) ? $inputs[$key . $remark_suffix] : '';
//                    dd($key . ':' . $inputs[$key . $remark_suffix]);
                    $biddinginformationitem->update(['value' => $value, 'remark' => $remark]);
                }
            }
        }
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
        Excel::load($file->getRealPath(), function ($reader) use ($request) {
            $biddinginformationdefinefields = Biddinginformationdefinefield::all();

            $objExcel = $reader->getExcel();
            for ($i = 0; $i < $objExcel->getSheetCount(); $i++)
            {
                $sheet = $objExcel->getSheet($i);
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                $highestColumn++;

                //  Loop through each row of the worksheet in turn
                $keys = [];
                for ($row = 1; $row <= $highestRow; $row++)
                {
                    if ($row == 1)
                    {
                        //  Read a row of data into an array
                        $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                            NULL, TRUE, FALSE);

                        // 第一行，关键字
                        $keys = $rowData[0];
                    }
                    else
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
                        for ($colIndex = 'A'; $colIndex != $highestColumn; $colIndex++)
                        {
                            // 组装单元格标识  A1  A2
                            $addr = $colIndex . $row;
                            // 获取单元格内容
//                            $cell = $sheet->getCell($addr)->getValue();
                            $cell = $sheet->getCell($addr)->getCalculatedValue();
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

                        if (array_key_exists('序号', $input) && !empty($input['序号'][0]))
                        {
//                            dd($input['序号'][0]);
                            {
                                $data = [];
                                $biddinginformation = Biddinginformation::create($data);
//                                dd($biddinginformation);
                                foreach ($biddinginformationdefinefields as $biddinginformationdefinefield)
                                {
//                                    dd($input[$biddinginformationdefinefield->name]);
                                    $itemdata = [];
                                    $itemdata['biddinginformation_id']      = $biddinginformation->id;
                                    $itemdata['key']                           = $biddinginformationdefinefield->name;
                                    $itemdata['value']                         = isset($input[$biddinginformationdefinefield->name][0]) ? $input[$biddinginformationdefinefield->name][0] : '';
                                    $itemdata['remark']                        = isset($input[$biddinginformationdefinefield->name][1]) ? $input[$biddinginformationdefinefield->name][1] : '';
                                    $itemdata['sort']                          = $biddinginformationdefinefield->sort;
                                    $itemdata['type']                          = $biddinginformationdefinefield->type;
//                                    Log::info($itemdata);
                                    Biddinginformationitem::create($itemdata);
                                }
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

        return redirect('basic/biddinginformations');
    }

    /**
     * export to excel/pdf.
     *
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        //
        // Excel::create('test1111')->export('xlsx');

        $filename = 'BAOJIA';
//        $filename = iconv("UTF-8","GBK//IGNORE", '中标信息');
        Excel::create($filename, function($excel) use ($request) {
            $excel->sheet('Sheet1', function($sheet) use ($request) {
                $biddinginformations = $this->searchrequest($request)->get();
                $biddinginformationdefinefields = Biddinginformationdefinefield::orderBy('sort')->get();
                $data = [];
                foreach ($biddinginformationdefinefields as $biddinginformationdefinefield)
                {
                    array_push($data, $biddinginformationdefinefield->name);
                }
                $sheet->appendRow($data);
                $rowCol = 2;        // 从第二行开始
                foreach ($biddinginformations as $biddinginformation)
                {
                    $data = [];
                    $comments = [];
                    foreach ($biddinginformationdefinefields as $biddinginformationdefinefield)
                    {
                        $biddinginformationitem = Biddinginformationitem::where('biddinginformation_id', $biddinginformation->id)->where('key', $biddinginformationdefinefield->name)->first();
                        array_push($data, isset($biddinginformationitem) ? $biddinginformationitem->value : '');
                        array_push($comments, isset($biddinginformationitem) ? $biddinginformationitem->remark : '');
                    }
                    $sheet->appendRow($data);

                    // 添加批注
                    $colIndex = 'A';
                    foreach ($comments as $comment)
                    {
                        if (strlen($comment) > 0)
                            $sheet->getComment($colIndex . $rowCol)->getText()->createTextRun($comment);
                        $colIndex++;
                    }
                    $rowCol++;
                }

//                $sheet->fromArray($biddinginformation["data"]);
            });

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

        // // instantiate and use the dompdf class
        // $dompdf = new Dompdf();
        // // $dompdf->loadHtml('hello world');
        // // $dompdf->set_option('isRemoteEnabled', true);
        // // $dompdf->loadHtmlFile(url('/approval/paymentrequests/25'));
        // $dompdf->loadHtmlFile('http://www.baidu.com');
        // // $html = file_get_contents('http://www.baidu.com');
        // // return $html;

        // // (Optional) Setup the paper size and orientation
        // $dompdf->setPaper('A4', 'landscape');

        // // Render the HTML as PDF
        // $dompdf->render();

        // // Output the generated PDF to Browser
        // $dompdf->stream();

        // return PDF::loadFile(url('/approval/paymentrequests/25'))->save('/path-to/my_stored_file.pdf')->stream('download.pdf');

        // return 'ssss';
    }

    // https://www.cnblogs.com/cyclzdblog/p/7670695.html
    public function downloadfile($filename)
    {
//        Log::info('filename: ' . $filename);
//        $newfilename = substr($filename, 0, strpos($filename, ".")) . Carbon::now()->format('YmdHis') . substr($filename, strpos($filename, "."));
//        Log::info($newfilename);
//        rename(public_path('download/shipment/' . $filename), public_path('download/shipment/' . $newfilename));
//        $file = public_path('download/biddinginformations/' . iconv("GBK//IGNORE","UTF-8", $filename));
        $file = public_path('download/biddinginformations/' . $filename);
        Log::info('file path:' . $file);
        return response()->download($file);
    }
}
