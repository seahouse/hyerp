<?php

namespace App\Http\Controllers\Basic;

use App\Models\Basic\Biddinginformation;
use App\Models\Basic\Biddinginformationdefinefield;
use App\Models\Basic\Biddinginformationitem;
use App\Models\Basic\Biddingproject;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Excel,DB,Log;

class BiddingprojectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $biddingprojects = Biddingproject::latest('created_at')->paginate(15);
        $inputs = $request->all();
        return view('basic.biddingprojects.index', compact('biddingprojects','inputs'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('basic.biddingprojects.create');
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
            'name' => 'required',
        ]);
        $input = $request->all();
//        dd($input);
        Biddingproject::create($input);

        return redirect('basic/biddingprojects');
    }

    public function getitemsbykey($key)
    {

        $query = Biddingproject::where('name', 'like', '%'.$key.'%')->orderBy('id', 'desc');

        $biddingprojects = $query->paginate(20);

        return response($biddingprojects);

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
        $biddingproject = Biddingproject::findOrFail($id);
        return view('basic.biddingprojects.edit', compact('biddingproject'));
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
        $this->validate($request, [
            'name' => 'required',
        ]);
        $biddingproject = Biddingproject::findOrFail($id);
        $biddingproject->update($request->all());

        return redirect('basic/biddingprojects');
    }

    public function showbiddinginformation($id)
    {
        $inputs='';
        $biddinginformations =Biddinginformation::where('biddingprojectid','=',$id)->paginate(15);
        return view('basic.biddingprojects.showbiddinginformation',compact('biddinginformations','id','inputs'));
    }

    public function deletebiddinginformation($informationid)
    {
//        dd($informationid);
        $biddinginformation = Biddinginformation::findOrFail($informationid);
        $id=$biddinginformation->biddingprojectid;
//        dd($id);
        $biddinginformation->biddingprojectid='';
        $biddinginformation->save();
        return redirect('/basic/biddingprojects/'.$id.'/showbiddinginformation');
    }

    public function export(Request $request)
    {
        $filename = 'Projectinfo';
        Excel::create($filename, function($excel) use ($request) {
            $excel->sheet('项目明细', function($sheet) use ($request) {
//                $query = Biddinginformationdefinefield::select('*');
//
//                $biddinginformationdefinefields = $query->orderBy('sort')->get();
                $query = Biddinginformationdefinefield::where(function ($query) {
                    $query->where('exceltype', '项目明细')
                        ->orWhere('exceltype', '汇总明细');
                });
                $biddinginformationdefinefields = $query->orderBy('sort')->get();
                $data = [];
                array_push($data, '项目名');
                array_push($data, '编号');
                foreach ($biddinginformationdefinefields as $biddinginformationdefinefield)
                {
                    array_push($data, $biddinginformationdefinefield->name);
                }
                $sheet->appendRow($data);
                $rowCol = 2;        // 从第二行开始
                $colCol=0;         //从第一列开始


//                $query = DB::table('biddinginformations')->leftjoin('biddingprojects','biddinginformations.biddingprojectid','=','biddingprojects.id')->select('biddingprojects.name','biddinginformation.id')->get();

                Biddingproject::chunk(100, function($biddingprojects) use ($sheet, $biddinginformationdefinefields, &$rowCol,&$colCol) {
                    foreach ($biddingprojects as $biddingproject)
                    {
                        $data = [];

                        array_push($data, $biddingproject->name);
                        $sheet->getCell(\PHPExcel_Cell::stringFromColumnIndex($colCol).$rowCol)->setValue($biddingproject->name);
                        $colCol++;
//                        dd($sheet->getCell(\PHPExcel_Cell::stringFromColumnIndex($colCol-1).$rowCol)->getValue());
                        $biddinginformations=DB::table('biddinginformations')->where('biddingprojectid',$biddingproject->id)->get();
//                        dd();
                        if(count($biddinginformations)>0) {
//                                dd($biddinginformation);
                            $biddinglist = '';
                            foreach ($biddinginformations as $biddinginformation)
                            {
                                if ($biddinglist <> '') {
                                    $biddinglist = $biddinglist . ';' . $biddinginformation->number;
                                } else if ($biddinglist == '') {
                                    $biddinglist = $biddinginformation->number;
                                }
                            }
                            $sheet->getCell(\PHPExcel_Cell::stringFromColumnIndex($colCol).$rowCol)->setValue($biddinglist);
                            $colCol++;

                           foreach ($biddinginformationdefinefields as $biddinginformationdefinefield)
                            {
                                $list='';

                                foreach($biddinginformations as $biddinginformation)
                                    {
//                                      $biddinginformationitem = $biddinginformation->biddinginformationitems()->where('key', $biddinginformationdefinefield->name)->first();
                                        $biddinginformationitem = Biddinginformationitem::where('biddinginformation_id', $biddinginformation->id)->where('key', $biddinginformationdefinefield->name)->select('key', 'value')->first();


                                        if(isset($biddinginformationitem) && $list<>'' )
                                        {
                                            $list= $list.';'. $biddinginformationitem->value;
                                        }
                                        else if(isset($biddinginformationitem) && $list=='')
                                        {
                                            $list= $biddinginformationitem->value;
                                        }

                                    }

                                $sheet->getCell(\PHPExcel_Cell::stringFromColumnIndex($colCol).$rowCol)->setValue($list);
                                $colCol++;
                             }


                        }

                        $rowCol++;
                        $colCol=0;
                    }
                });
//                $freezeCol = config('custom.bidding.freeze_detail_col', 'B');
//                $sheet->setFreeze($freezeCol . '2');
            });
            $excel->sheet('汇总表', function($sheet) use ($request) {
//                $query = Biddinginformationdefinefield::select('*');
//
//                $biddinginformationdefinefields = $query->orderBy('sort')->get();
                $query = Biddinginformationdefinefield::where(function ($query) {
                    $query->where('exceltype', '汇总表')
                        ->orWhere('exceltype', '汇总明细');
                });
                $biddinginformationdefinefields = $query->orderBy('sort')->get();
                $data = [];
                array_push($data, '项目名');
                array_push($data, '编号');
                foreach ($biddinginformationdefinefields as $biddinginformationdefinefield)
                {
                    array_push($data, $biddinginformationdefinefield->name);
                }
                $sheet->appendRow($data);
                $rowCol = 2;        // 从第二行开始
                $colCol=0;         //从第一列开始


//                $query = DB::table('biddinginformations')->leftjoin('biddingprojects','biddinginformations.biddingprojectid','=','biddingprojects.id')->select('biddingprojects.name','biddinginformation.id')->get();

                Biddingproject::chunk(100, function($biddingprojects) use ($sheet, $biddinginformationdefinefields, &$rowCol,&$colCol) {
                    foreach ($biddingprojects as $biddingproject)
                    {
                        $data = [];

                        array_push($data, $biddingproject->name);
                        $sheet->getCell(\PHPExcel_Cell::stringFromColumnIndex($colCol).$rowCol)->setValue($biddingproject->name);
                        $colCol++;
//                        dd($sheet->getCell(\PHPExcel_Cell::stringFromColumnIndex($colCol-1).$rowCol)->getValue());
                        $biddinginformations=DB::table('biddinginformations')->where('biddingprojectid',$biddingproject->id)->get();
//                        dd();
                        if(count($biddinginformations)>0)
                        {
//                                dd($biddinginformation);
                            $biddinglist = '';
                            foreach ($biddinginformations as $biddinginformation)
                            {
                                if ($biddinglist <> '') {
                                    $biddinglist = $biddinglist . ';' . $biddinginformation->number;
                                } else if ($biddinglist == '') {
                                    $biddinglist = $biddinginformation->number;
                                }
                            }
                            $sheet->getCell(\PHPExcel_Cell::stringFromColumnIndex($colCol).$rowCol)->setValue($biddinglist);
                            $colCol++;

                            foreach ($biddinginformationdefinefields as $biddinginformationdefinefield)
                            {
                                $list='';
                                foreach($biddinginformations as $biddinginformation)
                                {
//                                      $biddinginformationitem = $biddinginformation->biddinginformationitems()->where('key', $biddinginformationdefinefield->name)->first();
                                    $biddinginformationitem = Biddinginformationitem::where('biddinginformation_id', $biddinginformation->id)->where('key', $biddinginformationdefinefield->name)->select('key', 'value')->first();

                                    if(isset($biddinginformationitem) && $list<>'' )
                                    {
                                        $list= $list.';'. $biddinginformationitem->value;
                                    }
                                    else if(isset($biddinginformationitem) && $list=='')
                                    {
                                        $list= $biddinginformationitem->value;
                                    }

                                }
                                $sheet->getCell(\PHPExcel_Cell::stringFromColumnIndex($colCol).$rowCol)->setValue($list);
                                $colCol++;
                            }


                        }

                        $rowCol++;
                        $colCol=0;
                    }
                });
//                $freezeCol = config('custom.bidding.freeze_detail_col', 'B');
//                $sheet->setFreeze($freezeCol . '2');
            });
        })->store('xlsx', public_path('download/biddingprojects'));

        $file = public_path('download/biddingprojects/' . $filename . '.xlsx');
        Log::info('file path:' . $file);
//        dd($file);
        return response()->download($file);
//        return route('basic.biddingprojects.downloadfile', ['filename' => $filename . '.xlsx']);
    }

    public function downloadfile($filename)
    {
        $file = public_path('download/biddingprojects/' . $filename);
        Log::info('file path:' . $file);
//        dd($file);
        return response()->download($file);
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
        Biddingproject::destroy($id);
        return redirect('basic/biddingprojects');
    }
}
