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
                    Biddinginformationitem::create([
                        'biddinginformation_id' => $biddinginformation->id,
                        'key' => $key,
                        'value' => $value,
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
        foreach ($inputs as $key => $value)
        {
            $biddinginformationitem = Biddinginformationitem::where('biddinginformation_id', $id)->where('key', $key)->first();
            if (isset($biddinginformationitem))
            {
//                dd($biddinginformationitem);
                $biddinginformationitem->update(['value' => $value]);
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
            $reader->each(function ($sheet) use (&$reader, $request, &$biddinginformationdefinefields) {
                Log::info('sheet: ' . $sheet->getTitle());
                $sheet->each(function ($row) use (&$reader, $request, &$biddinginformationdefinefields) {
//                        dd($row->all());
//                        $input = array_values($row->toArray());
                    $input = $row->all();
//                    dd($input);
//                    if (count($input) >= 24)
                    {
                        if (!empty($input['序号']))
                        {
//                            dd($input['序号']);
//                            $salarysheet = Salarysheet::where('username', $input['姓名'])->where('salary_date', $request->input('salary_date'))->first();
//                            if (!isset($salarysheet))
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
                                    $itemdata['value']                         = isset($input[$biddinginformationdefinefield->name]) ? $input[$biddinginformationdefinefield->name] : '';
                                    $itemdata['sort']                          = $biddinginformationdefinefield->sort;
                                    $itemdata['type']                          = $biddinginformationdefinefield->type;
//                                    Log::info($itemdata);
                                    Biddinginformationitem::create($itemdata);
                                }
//                                $data['salary_date'] = $request->input('salary_date');
//                                $data['username']               = $input['姓名'];
//                                $user = User::where('name', $input['姓名'])->first();
//                                if (isset($user))
//                                    $data['user_id']            = $user->id;
//                                $data['department']            = $input['部门'];
//                                $data['attendance_days']       = isset($input['出勤天数']) ? $input['出勤天数'] : 0.0;
//                                $data['basicsalary']            = isset($input['基本工资']) ? $input['基本工资'] : 0.0;
//                                $data['overtime_hours']        = isset($input['加班小时']) ? $input['加班小时'] : 0.0;
//                                $data['absenteeismreduce_hours'] = isset($input['缺勤减扣小时']) ? $input['缺勤减扣小时'] : 0.0;
//                                $data['paid_hours']             = isset($input['计薪小时']) ? $input['计薪小时'] : 0.0;
//                                $data['overtime_amount']       = isset($input['加班费']) ? $input['加班费'] : 0.0;
//                                $data['fullfrequently_award'] = isset($input['满勤奖']) ? $input['满勤奖'] : 0.0;
//                                $data['meal_amount']            = isset($input['餐贴']) ? $input['餐贴'] : 0.0;
//                                $data['car_amount']             = isset($input['车贴']) ? $input['车贴'] : 0.0;
//                                $data['business_amount']       = isset($input['外差补贴']) ? $input['外差补贴'] : 0.0;
//                                $data['additional_amount']     = isset($input['补资']) ? $input['补资'] : 0.0;
//                                $data['house_amount']           = isset($input['房贴']) ? $input['房贴'] : 0.0;
//                                $data['hightemperature_amount'] = isset($input['高温费']) ? $input['高温费'] : 0.0;
//                                $data['absenteeismreduce_amount'] = isset($input['缺勤扣款']) ? $input['缺勤扣款'] : 0.0;
//                                $data['shouldpay_amount']       = isset($input['应发工资']) ? $input['应发工资'] : 0.0;
//                                $data['borrowreduce_amount']   = isset($input['借款扣回']) ? $input['借款扣回'] : 0.0;
//                                $data['personalsocial_amount'] = isset($input['个人社保']) ? $input['个人社保'] : 0.0;
//                                $data['personalaccumulationfund_amount'] = isset($input['个人公积金']) ? $input['个人公积金'] : 0.0;
//                                $data['individualincometax_amount'] = isset($input['个人所得税']) ? $input['个人所得税'] : 0.0;
//                                $data['actualsalary_amount']    = isset($input['实发工资']) ? $input['实发工资'] : 0.0;
//                                $data['remark']                    = isset($input['备注']) ? $input['备注'] : '';
                            }
//                            else
                            {
//                                $user = User::where('name', $input['姓名'])->first();
//                                if (isset($user))
//                                    $salarysheet->user_id               = $user->id;
//                                $salarysheet->department                = $input['部门'];
//                                $salarysheet->attendance_days           = isset($input['出勤天数']) ? $input['出勤天数'] : 0.0;
//                                $salarysheet->basicsalary               = isset($input['基本工资']) ? $input['基本工资'] : 0.0;
//                                $salarysheet->overtime_hours            = isset($input['加班小时']) ? $input['加班小时'] : 0.0;
//                                $salarysheet->absenteeismreduce_hours = isset($input['缺勤减扣小时']) ? $input['缺勤减扣小时'] : 0.0;
//                                $salarysheet->paid_hours                = isset($input['计薪小时']) ? $input['计薪小时'] : 0.0;
//                                $salarysheet->overtime_amount        = isset($input['加班费']) ? $input['加班费'] : 0.0;
//                                $salarysheet->fullfrequently_award  = isset($input['满勤奖']) ? $input['满勤奖'] : 0.0;
//                                $salarysheet->meal_amount               = isset($input['餐贴']) ? $input['餐贴'] : 0.0;
//                                $salarysheet->car_amount                 = isset($input['车贴']) ? $input['车贴'] : 0.0;
//                                $salarysheet->business_amount           = isset($input['外差补贴']) ? $input['外差补贴'] : 0.0;
//                                $salarysheet->additional_amount         = isset($input['补资']) ? $input['补资'] : 0.0;
//                                $salarysheet->house_amount              = isset($input['房贴']) ? $input['房贴'] : 0.0;
//                                $salarysheet->hightemperature_amount = isset($input['高温费']) ? $input['高温费'] : 0.0;
//                                $salarysheet->absenteeismreduce_amount = isset($input['缺勤扣款']) ? $input['缺勤扣款'] : 0.0;
//                                $salarysheet->shouldpay_amount       = isset($input['应发工资']) ? $input['应发工资'] : 0.0;
//                                $salarysheet->borrowreduce_amount       = isset($input['借款扣回']) ? $input['借款扣回'] : 0.0;
//                                $salarysheet->personalsocial_amount     = isset($input['个人社保']) ? $input['个人社保'] : 0.0;
//                                $salarysheet->personalaccumulationfund_amount = isset($input['个人公积金']) ? $input['个人公积金'] : 0.0;
//                                $salarysheet->individualincometax_amount = isset($input['个人所得税']) ? $input['个人所得税'] : 0.0;
//                                $salarysheet->actualsalary_amount       = isset($input['实发工资']) ? $input['实发工资'] : 0.0;
//                                $salarysheet->remark                    = isset($input['备注']) ? $input['备注'] : '';
//                                $salarysheet->save();
                            }
                        }
                        else
                        {
//                                if (empty($input[3]) && !empty($input[5]) && isset($shipment))
//                                {
//                                    $input['shipment_id'] = $shipment->id;
//                                    $input['contract_number'] = $input[5];
//                                    $input['purchaseorder_number'] = $input[7];
//                                    $input['qty_for_customer'] = $input[39];
//                                    $input['amount_for_customer'] = $input[40];
//                                    $input['volume'] = $input[53];
//                                    Shipmentitem::create($input);
//                                }
                        }
                    }
                });
            });

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

        $filename = iconv("UTF-8","GBK//IGNORE", '中标信息');
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
                foreach ($biddinginformations as $biddinginformation)
                {
                    $data = [];
                    foreach ($biddinginformationdefinefields as $biddinginformationdefinefield)
                    {
                        $biddinginformationitem = Biddinginformationitem::where('biddinginformation_id', $biddinginformation->id)->where('key', $biddinginformationdefinefield->name)->first();
                        array_push($data, isset($biddinginformationitem) ? $biddinginformationitem->value : '');
                    }
                    $sheet->appendRow($data);
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
        $file = public_path('download/biddinginformations/' . iconv("GBK//IGNORE","UTF-8", $filename));
//        Log::info('file path:' . $file);
        return response()->download($file);
    }
}
