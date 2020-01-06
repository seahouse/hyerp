<?php

namespace App\Http\Controllers\System;

use App\Models\System\Salarysheet;
use App\Models\System\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Excel, Log;

class SalarysheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
//        $request = request();
//        $key = $request->input('key', '');
//        $inputs = $request->all();
//        if (null !== request('key'))
//            $users = $this->searchrequest($request);
//        else
//            $users = User::latest('created_at')->paginate(10);
//        if (null !== request('key'))
//            return view('system.users.index', compact('users', 'key', 'inputs'));
//        else
//            return view('system.users.index', compact('users'));

        $salarysheets = Salarysheet::latest('created_at')->paginate(10);
        return view('system.salarysheets.index', compact('salarysheets'));
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

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function import()
    {
        //
        return view('system.salarysheets.import');
    }

    public function importstore(Request $request)
    {
        //
        $input = $request->all();
        $file = $request->file('file');
//        dd($file->getRealPath());
//        $file = array_get($input,'file');
        $excel = [];
//        dd($file->public_path());
//        Log::info($request->getSession().getServletContext()->getReadPath("/xx"));

//        Excel::load(iconv('UTF-8', 'GBK', public_path("aaa.xls")) , function ($reader) {
//            dd($reader->get());
//            $reader->each(function ($sheet) {
//                $sheet->each(function ($row) {
//                    dd($row);
//                });
//            });
//        });

        // !! set config/excel.php
        // 'force_sheets_collection' => true,   // !!
        Excel::load($file->getRealPath(), function ($reader) use (&$excel, $request) {
            $reader->each(function ($sheet) use (&$reader, $request) {
                Log::info('sheet: ' . $sheet->getTitle());
                $rowindex = 2;
                $shipment = null;
                $sheet->each(function ($row) use (&$rowindex, &$shipment, &$reader, $request) {
//                    Log::info('importstore 1: ');
//                    if ($rowindex > 3)
                    {
//                        dd($row->all());
//                        $input = array_values($row->toArray());
                        $input = $row->all();
//                        dd($input);
                        if (count($input) >= 24)
                        {
                            if (!empty($input['姓名']))
                            {
//                                dd($input['姓名']);
//                                $shipment = Shipment::where('invoice_number', $input[3])->first();
//                                if (!isset($shipment))
                                {
                                    $data = [];
                                    $data['salary_date'] = $request->input('salary_date');
                                    $data['username']               = $input['姓名'];
                                    $user = User::where('name', $input['姓名'])->first();
                                    if (isset($user))
                                        $data['user_id']            = $user->id;
                                    $data['department']            = $input['部门'];
                                    $data['attendance_days']       = $input['出勤天数'];
                                    $data['basicsalary']            = $input['基本工资'];
                                    $data['overtime_hours']        = $input['加班小时'];
                                    $data['absenteeism_reduce']    = $input['缺勤减扣'];
                                    $data['paid_hours']             = $input['计薪小时'];
                                    $data['overtime_amount']       = $input['加班费'];
                                    $data['fullfrequently_award'] = $input['满勤奖'];
                                    $data['meal_amount']            = $input['餐贴'];
                                    $data['car_amount']             = $input['车贴'];
                                    $data['business_amount']       = $input['外差补贴'];
                                    $data['additional_amount']     = $input['补资'];
                                    $data['house_amount']           = $input['房贴'];
                                    $data['hightemperature_amount'] = $input['高温费'];
                                    $data['shouldpay_amount']       = $input['应发工资'];
                                    $data['borrowreduce_amount']   = $input['借款扣回'];
                                    $data['personalsocial_amount'] = $input['个人社保'];
                                    $data['personalaccumulationfund_amount'] = $input['个人公积金'];
                                    $data['individualincometax_amount'] = $input['个人所得税'];
                                    $data['actualsalary_amount']    = $input['实发工资'];
                                    $data['remark']                    = $input['备注'];
//                                    dd($data);
                                    $salarysheet = Salarysheet::create($data);
                                }
//                                else
//                                    $shipment = null;
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
                    }
                    $rowindex++;
                });
            });

            $objExcel = $reader->getExcel();
            $sheet = $objExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
            Log::info('highestRow: ' . $highestRow);
            Log::info('highestColumn: ' . $highestColumn);

            //  Loop through each row of the worksheet in turn
            for ($row = 1; $row <= $highestRow; $row++)
            {
                //  Read a row of data into an array
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                    NULL, TRUE, FALSE);

                $excel[] = $rowData[0];
            }
        });
//        dd($file->getRealPath());
//        Shipment::create($input);

        return redirect('system/salarysheet');
    }
}
