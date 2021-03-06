<?php

namespace App\Http\Controllers\Approval;

use App\Http\Controllers\DingTalkController;
use App\Http\Controllers\util\taobaosdk\dingtalk\DingTalkClient;
use App\Http\Controllers\util\taobaosdk\dingtalk\request\CorpMessageCorpconversationAsyncsendRequest;
use App\Http\Controllers\util\taobaosdk\dingtalk\request\OapiProcessinstanceCspaceInfoRequest;
use App\Http\Controllers\util\taobaosdk\dingtalk\request\OapiProcessinstanceGetRequest;
use App\Models\Approval\Epcsecening;
use App\Models\Approval\Epcseceningattachment;
use App\Models\Approval\Epcseceningcrane;
use App\Models\Approval\Epcseceninghumanday;
use App\Models\Approval\Epcseceningmaterial;
use App\Models\Approval\Epcseceningoptrecord;
use App\Models\System\Dtuser;
use App\Models\System\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth, Storage, Log;

class EpcseceningController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
//        self::updateStatusByProcessInstanceId('c907f3e2-e879-4117-8164-979517626557', 0);
//        return;

        $request = request();
        $inputs = $request->all();
        $epcsecenings = $this->searchrequest($request)->paginate(15);

        return view('approval.epcsecenings.index', compact('epcsecenings', 'inputs'));
    }

    public function searchrequest($request)
    {
        $query = Epcsecening::latest();

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
            }
        }

        $items = $query->select('epcsecenings.*');

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

    public function mcreate()
    {
        //
        $config = DingTalkController::getconfig();
        $client = new DingTalkClient();
        $req = new OapiProcessinstanceCspaceInfoRequest();
        $req->setUserId(Auth::user()->dtuserid);
        $response = $client->execute($req, $config['session']);
//        dd(json_decode(json_encode($response))->result->space_id);
        $config['spaceid'] = json_decode(json_encode($response))->result->space_id;
        return view('approval/epcsecenings/mcreate', compact('config'));
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

    public function mstore(Request $request)
    {
        $inputs = $request->all();
//        dd($inputs);

        $this->validate($request, [
            'sohead_id'                   => 'required|integer|min:1',
//            'amounttype'               => 'required',
//            'supplier_id'             => 'required',
//            'issuedrawing_values'       => 'required',
//            'items_string'               => 'required',
//            'tonnage'               => 'required|numeric',
//            'drawingchecker_id'     => 'required|integer|min:1',
//            'drawingcount'          => 'required|integer|min:1',
//            'drawingattachments.*'  => 'required|file',
//            'images.*'                => 'required|image',
//            'associated_approval_projectpurchase'            => 'required',
        ]);
//        dd($input);
//        $input = HelperController::skipEmptyValue($input);


        $inputs['applicant_id'] = Auth::user()->id;


        $epcsecening = Epcsecening::create($inputs);
//        dd($epcsecening);

        // create epcseceningmaterials
        if (isset($epcsecening))
        {
            $epcseceningmaterial_items = json_decode($inputs['items_string']);
            foreach ($epcseceningmaterial_items as $value) {
                if ($value->item_id > 0)
                {
                    $item_array = json_decode(json_encode($value), true);
                    $item_array['epcsecening_id'] = $epcsecening->id;
                    Epcseceningmaterial::create($item_array);
                }
            }
        }

        // create epcseceninghumandays
        if (isset($epcsecening))
        {
            $epcseceninghumanday_items = json_decode($inputs['items_string_humanday']);
            foreach ($epcseceninghumanday_items as $value) {
                if (strlen($value->humandays_type) > 0)
                {
                    $item_array = json_decode(json_encode($value), true);
                    $item_array['epcsecening_id'] = $epcsecening->id;
                    Epcseceninghumanday::create($item_array);
                }
            }
        }

        // create ecpseceningcranes
        if (isset($epcsecening))
        {
            $epcseceningcrane_items = json_decode($inputs['items_string_crane']);
            foreach ($epcseceningcrane_items as $value) {
                if (strlen($value->crane_type) > 0)
                {
                    $item_array = json_decode(json_encode($value), true);
                    $item_array['epcsecening_id'] = $epcsecening->id;
                    Epcseceningcrane::create($item_array);
                }
            }
        }

        // create files

        // 双方签字的安装队工作量表, bothsigned
        $fileattachments_url = [];
        $fileattachments_url2 = [];
        if (isset($epcsecening))
        {
            $files = array_get($inputs,'bothsigned');
            $destinationPath = 'uploads/approval/epcsecening/' . $epcsecening->id . '/bothsigned/';
            if (isset($files))
            {
                foreach ($files as $key => $file) {
                    if ($file)
                    {
                        $originalName = $file->getClientOriginalName();         // aa.xlsx
                        $extension = $file->getClientOriginalExtension();       // .xlsx
//                    Log::info('extension: ' . $extension);
                        $filename = date('YmdHis').rand(100, 200) . '.' . $extension;
                        Storage::put($destinationPath . $filename, file_get_contents($file->getRealPath()));

                        // $fileName = rand(11111, 99999) . '.' . $extension;
                        $upload_success = $file->move($destinationPath, $filename);

                        // add database record
                        $epcseceningattachment = new Epcseceningattachment();
                        $epcseceningattachment->epcsecening_id = $epcsecening->id;
                        $epcseceningattachment->type = "bothsigned";
                        $epcseceningattachment->filename = $originalName;
                        $epcseceningattachment->path = "/$destinationPath$filename";     // add a '/' in the head.
                        $epcseceningattachment->save();

                        array_push($fileattachments_url, url($destinationPath . $filename));
                        if (strcasecmp($extension, "pdf") == 0)
                            array_push($fileattachments_url2, url('pdfjs/viewer') . "?file=" . "/$destinationPath$filename");
                        else
                        {
                            $filename2 = str_replace(".", "_", $filename);
                            array_push($fileattachments_url2, url("$destinationPath$filename2"));
                        }
                    }
                }
            }
        }

        // 华星东方下发的工作联系单：huaxingworksheet
        $fileattachments_url = [];
        $fileattachments_url2 = [];
        if (isset($epcsecening))
        {
            $files = array_get($inputs,'huaxingworksheet');
            $destinationPath = 'uploads/approval/epcsecening/' . $epcsecening->id . '/huaxingworksheet/';
            if (isset($files))
            {
                foreach ($files as $key => $file) {
                    if ($file)
                    {
                        $originalName = $file->getClientOriginalName();         // aa.xlsx
                        $extension = $file->getClientOriginalExtension();       // .xlsx
//                    Log::info('extension: ' . $extension);
                        $filename = date('YmdHis').rand(100, 200) . '.' . $extension;
                        Storage::put($destinationPath . $filename, file_get_contents($file->getRealPath()));

                        // $fileName = rand(11111, 99999) . '.' . $extension;
                        $upload_success = $file->move($destinationPath, $filename);

                        // add database record
                        $epcseceningattachment = new Epcseceningattachment();
                        $epcseceningattachment->epcsecening_id = $epcsecening->id;
                        $epcseceningattachment->type = "huaxingworksheet";
                        $epcseceningattachment->filename = $originalName;
                        $epcseceningattachment->path = "/$destinationPath$filename";     // add a '/' in the head.
                        $epcseceningattachment->save();

                        array_push($fileattachments_url, url($destinationPath . $filename));
                        if (strcasecmp($extension, "pdf") == 0)
                            array_push($fileattachments_url2, url('pdfjs/viewer') . "?file=" . "/$destinationPath$filename");
                        else
                        {
                            $filename2 = str_replace(".", "_", $filename);
                            array_push($fileattachments_url2, url("$destinationPath$filename2"));
                        }
                    }
                }
            }
        }

        // 安装队下发的工作联系单：installworksheet
        $fileattachments_url = [];
        $fileattachments_url2 = [];
        if (isset($epcsecening))
        {
            $files = array_get($inputs,'installworksheet');
            $destinationPath = 'uploads/approval/epcsecening/' . $epcsecening->id . '/installworksheet/';
            if (isset($files))
            {
                foreach ($files as $key => $file) {
                    if ($file)
                    {
                        $originalName = $file->getClientOriginalName();         // aa.xlsx
                        $extension = $file->getClientOriginalExtension();       // .xlsx
//                    Log::info('extension: ' . $extension);
                        $filename = date('YmdHis').rand(100, 200) . '.' . $extension;
                        Storage::put($destinationPath . $filename, file_get_contents($file->getRealPath()));

                        // $fileName = rand(11111, 99999) . '.' . $extension;
                        $upload_success = $file->move($destinationPath, $filename);

                        // add database record
                        $epcseceningattachment = new Epcseceningattachment();
                        $epcseceningattachment->epcsecening_id = $epcsecening->id;
                        $epcseceningattachment->type = "installworksheet";
                        $epcseceningattachment->filename = $originalName;
                        $epcseceningattachment->path = "/$destinationPath$filename";     // add a '/' in the head.
                        $epcseceningattachment->save();

                        array_push($fileattachments_url, url($destinationPath . $filename));
                        if (strcasecmp($extension, "pdf") == 0)
                            array_push($fileattachments_url2, url('pdfjs/viewer') . "?file=" . "/$destinationPath$filename");
                        else
                        {
                            $filename2 = str_replace(".", "_", $filename);
                            array_push($fileattachments_url2, url("$destinationPath$filename2"));
                        }
                    }
                }
            }
        }

        // 增补之前图片：beforeimage
        $beforeimage_urls = [];
        // create images in the desktop
        if ($epcsecening)
        {
            $files = array_get($inputs,'beforeimage');
            $destinationPath = 'uploads/approval/epcsecening/' . $epcsecening->id . '/beforeimage/';
            if ($files)
            {
                foreach ($files as $key => $file) {
                    if ($file)
                    {
                        $originalName = $file->getClientOriginalName();
                        $extension = $file->getClientOriginalExtension();       // .xlsx
                        $filename = date('YmdHis').rand(100, 200) . '.' . $extension;
                        Storage::put($destinationPath . $filename, file_get_contents($file->getRealPath()));

                        $extension = $file->getClientOriginalExtension();
                        $filename = date('YmdHis').rand(100, 200) . '.' . $extension;
                        // $fileName = rand(11111, 99999) . '.' . $extension;
                        $upload_success = $file->move($destinationPath, $filename);

                        // add database record
                        $epcseceningattachment = new Epcseceningattachment();
                        $epcseceningattachment->epcsecening_id = $epcsecening->id;
                        $epcseceningattachment->type = "beforeimage";
                        $epcseceningattachment->filename = $originalName;
                        $epcseceningattachment->path = "/$destinationPath$filename";     // add a '/' in the head.
                        $epcseceningattachment->save();

                        array_push($beforeimage_urls, url($destinationPath . $filename));
                    }
                }
            }
        }

        // 增补施工后图片：afterimage
        $afterimage_urls = [];
        // create images in the desktop
        if ($epcsecening)
        {
            $files = array_get($inputs,'afterimage');
            $destinationPath = 'uploads/approval/epcsecening/' . $epcsecening->id . '/afterimage/';
            if ($files)
            {
                foreach ($files as $key => $file) {
                    if ($file)
                    {
                        $originalName = $file->getClientOriginalName();
                        $extension = $file->getClientOriginalExtension();       // .xlsx
                        $filename = date('YmdHis').rand(100, 200) . '.' . $extension;
                        Storage::put($destinationPath . $filename, file_get_contents($file->getRealPath()));

                        $extension = $file->getClientOriginalExtension();
                        $filename = date('YmdHis').rand(100, 200) . '.' . $extension;
                        // $fileName = rand(11111, 99999) . '.' . $extension;
                        $upload_success = $file->move($destinationPath, $filename);

                        // add database record
                        $epcseceningattachment = new Epcseceningattachment();
                        $epcseceningattachment->epcsecening_id = $epcsecening->id;
                        $epcseceningattachment->type = "afterimage";
                        $epcseceningattachment->filename = $originalName;
                        $epcseceningattachment->path = "/$destinationPath$filename";     // add a '/' in the head.
                        $epcseceningattachment->save();

                        array_push($afterimage_urls, url($destinationPath . $filename));
                    }
                }
            }
        }

        // 增补之前图片：beforeimage
        // create images from dingtalk mobile
        if ($epcsecening)
        {
            $images = array_where($inputs, function($key, $value) {
                if (substr_compare($key, 'beforeimage_', 0, 12) == 0)
                    return $value;
            });

            $destinationPath = 'uploads/approval/epcsecening/' . $epcsecening->id . '/beforeimage/';
            foreach ($images as $key => $value) {
                # code...

                // save image file.
                $sExtension = substr($value, strrpos($value, '.') + 1);
                // $sFilename = 'approval/reimbursement/' . $reimbursement->id .'/' . date('YmdHis').rand(100, 200) . '.' . $sExtension;
                // Storage::disk('local')->put($sFilename, file_get_contents($value));
                // Storage::move($sFilename, '../abcd.jpg');
                $dir = 'images/approval/epcsecening/' . $epcsecening->id . '/' . date('YmdHis').rand(100, 200) . '.' . $sExtension;
                $parts = explode('/', $dir);
                $filename = array_pop($parts);
                $dir = '';
                foreach ($parts as $part) {
                    # code...
                    $dir .= "$part/";
                    if (!is_dir($dir)) {
                        mkdir($dir);
                    }
                }

//                $originalName = $file->getClientOriginalName();
                Storage::put($destinationPath . $filename, file_get_contents($value));

                file_put_contents("$dir/$filename", file_get_contents($value));


                // add image record
                $epcseceningattachment = new Epcseceningattachment();
                $epcseceningattachment->epcsecening_id = $epcsecening->id;
                $epcseceningattachment->type = "beforeimage";     // add a '/' in the head.
                $epcseceningattachment->path = "/$dir$filename";     // add a '/' in the head.
                $epcseceningattachment->save();

                array_push($beforeimage_urls, $value);
            }
        }

        // 增补施工后图片：afterimage
        // create images from dingtalk mobile
        if ($epcsecening)
        {
            $images = array_where($inputs, function($key, $value) {
                if (substr_compare($key, 'afterimage_', 0, 12) == 0)
                    return $value;
            });

            $destinationPath = 'uploads/approval/epcsecening/' . $epcsecening->id . '/afterimage/';
            foreach ($images as $key => $value) {
                # code...

                // save image file.
                $sExtension = substr($value, strrpos($value, '.') + 1);
                // $sFilename = 'approval/reimbursement/' . $reimbursement->id .'/' . date('YmdHis').rand(100, 200) . '.' . $sExtension;
                // Storage::disk('local')->put($sFilename, file_get_contents($value));
                // Storage::move($sFilename, '../abcd.jpg');
                $dir = 'images/approval/epcsecening/' . $epcsecening->id . '/' . date('YmdHis').rand(100, 200) . '.' . $sExtension;
                $parts = explode('/', $dir);
                $filename = array_pop($parts);
                $dir = '';
                foreach ($parts as $part) {
                    # code...
                    $dir .= "$part/";
                    if (!is_dir($dir)) {
                        mkdir($dir);
                    }
                }

//                $originalName = $file->getClientOriginalName();
                Storage::put($destinationPath . $filename, file_get_contents($value));

                file_put_contents("$dir/$filename", file_get_contents($value));


                // add image record
                $epcseceningattachment = new Epcseceningattachment();
                $epcseceningattachment->epcsecening_id = $epcsecening->id;
                $epcseceningattachment->type = "afterimage";     // add a '/' in the head.
                $epcseceningattachment->path = "/$dir$filename";     // add a '/' in the head.
                $epcseceningattachment->save();

                array_push($afterimage_urls, $value);
            }
        }
//        dd($epcsecening);

        if (isset($epcsecening))
        {
            $inputs['beforeimage_urls'] = json_encode($beforeimage_urls);
            $inputs['afterimage_urls'] = json_encode($afterimage_urls);
//            $inputs['approvers'] = $epcsecening->approvers();
            $inputs['associatedapprovals'] = strlen($inputs['associatedapprovals']) > 0 ? json_encode(explode(",", $inputs['associatedapprovals'])) : "";
            $response = ApprovalController::epcsecening($inputs);
//            Log::info($response);
//            dd($response);
            $responsejson = json_decode($response);
            if ($responsejson->errcode <> "0")
            {
                $epcsecening->forceDelete();
//                Log::info(json_encode($inputs));
                dd('钉钉端创建失败: ' . $responsejson->errmsg);
            }
            else
            {
                // save process_instance_id and business_id
                $process_instance_id = $responsejson->process_instance_id;

                $response = DingTalkController::processinstance_get($process_instance_id);
                $responsejson = json_decode($response);
                $business_id = '';
                if ($responsejson->dingtalk_smartwork_bpms_processinstance_get_response->result->ding_open_errcode == 0)
                    $business_id = $responsejson->dingtalk_smartwork_bpms_processinstance_get_response->result->process_instance->business_id;

                $epcsecening->process_instance_id = $process_instance_id;
                $epcsecening->business_id = $business_id;
                $epcsecening->save();
            }
        }


        dd('创建成功.');
        return redirect('approval/mindexmy');
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

    public static function updateStatusByProcessInstanceId($processInstanceId, $status)
    {
        $epcsecening = Epcsecening::where('process_instance_id', $processInstanceId)->firstOrFail();
        if (isset($epcsecening))
        {
            $epcsecening->status = $status;
            $epcsecening->save();

            if ($status == 0)
            {
                // 下载操作记录
                Epcseceningoptrecord::where('epcsecening_id', $epcsecening->id)->delete();

                $client = new DingTalkClient();
                $req = new OapiProcessinstanceGetRequest();
                $req->setProcessInstanceId($epcsecening->process_instance_id);
                $accessToken = DingTalkController::getAccessToken();
                $response = $client->execute($req, $accessToken);
                $response = json_decode(json_encode($response, JSON_UNESCAPED_UNICODE));
                if ($response->errcode == "0")
                {
                    $remark = '';
                    $dtuser_whl = Dtuser::where('user_id', 2)->first();

                    $operation_records = $response->process_instance->operation_records->operation_records_vo;
                    foreach ($operation_records as $operation_record)
                    {
                        $item_array = json_decode(json_encode($operation_record), true);
                        $item_array['epcsecening_id'] = $epcsecening->id;
                        if (isset($operation_record->remark) && is_array($item_array['remark']))
                        {
                            $item_array['remark'] = json_encode($operation_record->remark);
                        }
                        Epcseceningoptrecord::create($item_array);

                        if (isset($dtuser_whl))
                        {
                            if (($operation_record->operation_type == 'ADD_REMARK' || $operation_record->operation_type == 'EXECUTE_TASK_NORMAL') && $operation_record->userid == $dtuser_whl->userid && isset($operation_record->remark))
                            {
                                $remark = $operation_record->remark;
                            }
                        }
                    }
                    if (strlen($remark) > 0)
                    {
                        Log::info($epcsecening->process_instance_id . "\t" . $epcsecening->business_id . "\t" . $remark);
                        $epcsecening->remark_whl = $remark;

                        $pattern = '/同意增补：(\d+(.\d+))元/';
                        if (preg_match($pattern, $remark, $matches))
                        {
                            if (!isset($epcsecening->amount_whl))
                                $epcsecening->amount_whl = $matches[1];
                        }
                        $epcsecening->save();
                    }
                }

                // 发送修改定价通知
                if (env('APP_DEBUG', true))
                    $user_whl = User::where('email', 'liangyi')->first();
                else
                    $user_whl = User::where('email', 'wuhaolun@huaxing-east.com')->first();
                if (isset($user_whl))
                {
                    $data = [
                        [
                            'key' => '审批编号:',
                            'value' => $epcsecening->business_id,
                        ],
                        [
                            'key' => '增补项所属设计部门:',
                            'value' => $epcsecening->additional_design_department,
                        ],
                        [
                            'key' => '造成增补的责任归集部门:',
                            'value' => $epcsecening->additional_source_department,
                        ],
                        [
                            'key' => '发起人:',
                            'value' => $epcsecening->applicant->name,
                        ],
                    ];

                    $msgcontent_data = [
                        'message_url' => url('mddauth/approval/approval-epcsecening-' . $epcsecening->id . '-meditamount'),
                        'pc_message_url' => '',
                        'head' => [
                            'bgcolor' => 'FFBBBBBB',
                            'text' => $epcsecening->applicant->name . '提交的EPC-安装队现场增补审批单已审批通过'
                        ],
                        'body' => [
                            'title' => '点此可进入查看内容和设置定价。',
                            'form' => $data
                        ]
                    ];
                    $msgcontent = json_encode($msgcontent_data);

                    $c = new DingTalkClient;
                    $req = new CorpMessageCorpconversationAsyncsendRequest;

                    $access_token = DingTalkController::getAccessToken();
                    $req->setAgentId(config('custom.dingtalk.agentidlist.approval'));
                    $req->setUseridList($user_whl->dtuserid);

                    $req->setMsgtype("oa");
                    //                $req->setDeptIdList("");
                    $req->setToAllUser("false");
                    $req->setMsgcontent("$msgcontent");
                    $resp = $c->execute($req, $access_token);
                    Log::info(json_encode($resp));
                    if ($resp->code != "0") {
                        Log::info($resp->msg . ": " . $resp->sub_msg);
                    }
                }
            }
        }
    }

    public static function deleteByProcessInstanceId($processInstanceId)
    {
        $epcsecening = Epcsecening::where('process_instance_id', $processInstanceId)->firstOrFail();
        if ($epcsecening)
        {
            $epcsecening->forceDelete();
        }
    }

    public static function export_wlhremark()
    {
        Epcsecening::where('status', 0)->chunk(200, function ($epcsecenings) {
            $client = new DingTalkClient();
            $req = new OapiProcessinstanceGetRequest();
            foreach ($epcsecenings as $epcsecening)
            {
                $remark = '';
                $req->setProcessInstanceId($epcsecening->process_instance_id);
                $accessToken = DingTalkController::getAccessToken();
                $response = $client->execute($req, $accessToken);
                $response = json_decode(json_encode($response, JSON_UNESCAPED_UNICODE));
                if ($response->errcode == "0")
                {
                    $operation_records = $response->process_instance->operation_records->operation_records_vo;
                    $dtuser_whl = Dtuser::where('user_id', 2)->first();
                    if (isset($dtuser_whl))
                    {
                        foreach ($operation_records as $operation_record)
                        {
                            if (($operation_record->operation_type == 'ADD_REMARK' || $operation_record->operation_type == 'EXECUTE_TASK_NORMAL') && $operation_record->userid == $dtuser_whl->userid && isset($operation_record->remark))
                            {
                                $remark = $operation_record->remark;
                            }
                        }
                        if (strlen($remark) > 0)
                        {
                            Log::info($epcsecening->process_instance_id . "\t" . $epcsecening->business_id . "\t" . $remark);
                            $epcsecening->remark_whl = $remark;

                            $pattern = '/同意增补：(\d+(.\d+))元/';
                            if (preg_match($pattern, $remark, $matches))
                            {
//                                dd($matches);
                                if (!isset($epcsecening->amount_whl))
                                    $epcsecening->amount_whl = $matches[1];
                            }
                            $epcsecening->save();
                        }
                    }
                }
            }
        });
        dd("导出完成。");
    }

    public function edittable()
    {
        $request = request();
        $inputs = $request->all();
        $epcsecenings = $this->searchrequest($request)->where('status', 0)->paginate(15);
        return view('approval.epcsecenings.edittable', compact('epcsecenings', 'inputs'));
    }

    public function updateedittable(Request $request)
    {
//        Log::info($request->all());
//        $inputs = $request->all();
//        dd($inputs);
        $id = $request->get('pk');
        $epcsecening = Epcsecening::findOrFail($id);
//        $name = $request->get('name');
        $value = $request->get('value');
        $epcsecening->amount_whl = $value;
        $epcsecening->save();
        return 'success';
    }

    public function meditamount($id)
    {
        //
        $epcsecening = Epcsecening::findOrFail($id);
        return view('approval.epcsecenings.meditamount', compact('epcsecening'));
    }

    public function updateamount(Request $request, $id)
    {
        //
        $epcsecening = Epcsecening::findOrFail($id);
        if ($request->has('amount_whl'))
        {
            $epcsecening->amount_whl = $request->input('amount_whl');
            $epcsecening->save();
            dd('设置成功。');
        }
        dd('设置失败。');
    }
}
