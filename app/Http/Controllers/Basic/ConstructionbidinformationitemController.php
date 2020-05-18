<?php

namespace App\Http\Controllers\Basic;

use App\Models\Basic\Constructionbidinformationitem;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Log;

class ConstructionbidinformationitemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    public function updateedittable(Request $request)
    {
        Log::info($request->all());
//        $inputs = $request->all();
//        dd($inputs);
        $id = $request->get('pk');
        $constructionbidinformationitem = Constructionbidinformationitem::findOrFail($id);
        $oldvalue = $constructionbidinformationitem->value;

        $name = $request->get('name');
        $value = $request->get('value');
        $constructionbidinformationitem->$name = $value;
        if ($constructionbidinformationitem->save())
        {
//            if ($oldvalue != $value)
//            {
//                $projectname = '';
//                $biddinginformationitem_mingcheng = Biddinginformationitem::where('biddinginformation_id', $id)->where('key', '名称')->first();
//                if (isset($biddinginformationitem_mingcheng))
//                    $projectname = $biddinginformationitem_mingcheng->value;
//
//                $msg = '[' . $projectname . ']项目[' . $biddinginformationitem->biddinginformation->number . ']的[' . $biddinginformationitem->key .']字段内容已修改。原内容：' . $oldvalue . '，新内容：' . $value;
//                $data = [
//                    'msgtype'       => 'text',
//                    'text' => [
//                        'content' => $msg
//                    ]
//                ];
//
////                $dtusers = Dtuser::where('user_id', 126)->orWhere('user_id', 126)->pluck('userid');        // test
//                $dtusers = Dtuser::where('user_id', 2)->orWhere('user_id', 64)->pluck('userid');             // WuHL, Zhoub
//                $useridList = implode(',', $dtusers->toArray());
////                            dd(implode(',', $dtusers->toArray()));
//                if ($dtusers->count() > 0)
//                {
//                    $agentid = config('custom.dingtalk.agentidlist.bidding');
//                    DingTalkController::sendWorkNotificationMessage($useridList, $agentid, json_encode($data));
//                }
//            }
        }
        return 'success';
    }
}
