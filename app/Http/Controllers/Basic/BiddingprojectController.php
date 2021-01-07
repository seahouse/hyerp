<?php

namespace App\Http\Controllers\Basic;

use App\Models\Basic\Biddinginformation;
use App\Models\Basic\Biddinginformationdefinefield;
use App\Models\Basic\Biddinginformationitem;
use App\Models\Sales\Project_hxold;
use App\Models\Sales\Salesorder_hxold;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Excel, DB, Log;

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
        $biddingprojects = Project_hxold::select(['id', 'name', 'descrip'])->withCount('biddinginformations')->paginate(15);
        // $biddingprojects = Project_hxold::from('vproject')
        //                    ->Join('vorder','vproject.id','=','vorder.project_id')
        //                    ->Join(DB::raw('[hxerp].[dbo].[biddinginformations]'),'vorder.id','=',DB::raw('[hxerp].[dbo].[biddinginformations].[sohead_id]'))
        //                    ->select('vproject.id','vproject.name',\DB::raw('count(*) as cntbidding'),'vproject.descrip')
        //                    ->groupby('vproject.id','vproject.name','vproject.descrip')
        //                    ->paginate(15);

        $inputs = $request->all();
        return view('basic.biddingprojects.index', compact('biddingprojects', 'inputs'));
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

        $query = Project_hxold::where('name', 'like', '%' . $key . '%')->orderBy('id', 'desc');

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
        $inputs = '';
        $saleorderids = Salesorder_hxold::join('hxcrm2016.dbo.vproject', 'vproject.id', '=', 'project_id')
            ->where('vproject.id', '=', $id)->pluck('vorder.id');
        $query = Biddinginformation::latest('created_at');
        $query->wherein('sohead_id', $saleorderids);
        $biddinginformations = $query->select('biddinginformations.*')->paginate(15);
        //        dd($biddinginformations);
        return view('basic.biddingprojects.showbiddinginformation', compact('biddinginformations', 'id', 'inputs'));
    }

    public function deletebiddinginformation($informationid)
    {
        //        dd($informationid);
        $biddinginformation = Biddinginformation::findOrFail($informationid);
        $id = $biddinginformation->biddingprojectid;
        //        dd($id);
        $biddinginformation->biddingprojectid = '';
        $biddinginformation->save();
        return redirect('/basic/biddingprojects/' . $id . '/showbiddinginformation');
    }

    public function export(Request $request)
    {
        $filename = 'Projectinfo';
        Excel::create($filename, function ($excel) use ($request) {
            $excel->sheet('项目明细', function ($sheet) use ($request) {
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
                array_push($data, '执行成本（动态计算）');
                array_push($data, '总纲耗（动态计算）');
                foreach ($biddinginformationdefinefields as $biddinginformationdefinefield) {
                    array_push($data, $biddinginformationdefinefield->name);
                }
                $sheet->appendRow($data);
                $rowCol = 2;        // 从第二行开始
                $colCol = 0;         //从第一列开始


                //                $query = DB::table('biddinginformations')->leftjoin('biddingprojects','biddinginformations.biddingprojectid','=','biddingprojects.id')->select('biddingprojects.name','biddinginformation.id')->get();

                Project_hxold::chunk(100, function ($biddingprojects) use ($sheet, $biddinginformationdefinefields, &$rowCol, &$colCol) {
                    foreach ($biddingprojects as $biddingproject) {
                        $data = [];

                        array_push($data, $biddingproject->name);
                        $sheet->getCell(\PHPExcel_Cell::stringFromColumnIndex($colCol) . $rowCol)->setValue($biddingproject->name);
                        $colCol++;
                        //                        dd($sheet->getCell(\PHPExcel_Cell::stringFromColumnIndex($colCol-1).$rowCol)->getValue());
                        $saleorderids = Salesorder_hxold::join('hxcrm2016.dbo.vproject', 'vproject.id', '=', 'project_id')
                            ->where('vproject.id', '=', $biddingproject->id)->pluck('vorder.id');
                        $query = Biddinginformation::latest('created_at');
                        $query->wherein('sohead_id', $saleorderids);
                        $biddinginformations = $query->select('biddinginformations.*')->get();

                        //                        $biddinginformations=DB::table('biddinginformations')->where('biddingprojectid',$biddingproject->id)->get();
                        //                        dd();
                        if (count($biddinginformations) > 0) {
                            //                                dd($biddinginformation);
                            $biddinglist = '';
                            foreach ($biddinginformations as $biddinginformation) {
                                if ($biddinglist <> '') {
                                    $biddinglist = $biddinglist . ';' . $biddinginformation->number;
                                } else if ($biddinglist == '') {
                                    $biddinglist = $biddinginformation->number;
                                }

                                $bExist = false;
                                $totalpurchasecost = 0;
                                $totalwarehousecost = 0;
                                $totaltonnage = 0;
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
                                        $totalpurchasecost = $totalpurchasecost + $totalpurchaseamount;
                                        $totalwarehousecost = $totalwarehousecost + $totalwarehouseamount;
                                        //                                        array_push($data, "采购成本：" . $totalpurchaseamount . "，出库成本：" . $totalwarehouseamount);

                                        $issuedrawing_tonnage = $sohead->issuedrawings()->where('status', 0)->sum('tonnage');
                                        $totaltonnage = $totaltonnage + $issuedrawing_tonnage;
                                        //                                        array_push($data, $issuedrawing_tonnage);
                                        $bExist = true;
                                    }
                                }
                            }
                            $sheet->getCell(\PHPExcel_Cell::stringFromColumnIndex($colCol) . $rowCol)->setValue($biddinglist);
                            $colCol++;
                            if ($bExist) {
                                $sheet->getCell(\PHPExcel_Cell::stringFromColumnIndex($colCol) . $rowCol)->setValue("采购成本：" . $totalpurchaseamount . "，出库成本：" . $totalwarehouseamount);
                                $colCol++;
                            } else {
                                $sheet->getCell(\PHPExcel_Cell::stringFromColumnIndex($colCol) . $rowCol)->setValue('');
                                $colCol++;
                            }

                            $sheet->getCell(\PHPExcel_Cell::stringFromColumnIndex($colCol) . $rowCol)->setValue($totaltonnage);
                            $colCol++;

                            foreach ($biddinginformationdefinefields as $biddinginformationdefinefield) {
                                $list = '';

                                foreach ($biddinginformations as $biddinginformation) {
                                    //                                      $biddinginformationitem = $biddinginformation->biddinginformationitems()->where('key', $biddinginformationdefinefield->name)->first();
                                    $biddinginformationitem = Biddinginformationitem::where('biddinginformation_id', $biddinginformation->id)->where('key', $biddinginformationdefinefield->name)->select('key', 'value')->first();


                                    if (isset($biddinginformationitem) && $list <> '') {
                                        $list = $list . ';' . $biddinginformationitem->value;
                                    } else if (isset($biddinginformationitem) && $list == '') {
                                        $list = $biddinginformationitem->value;
                                    }
                                }

                                $sheet->getCell(\PHPExcel_Cell::stringFromColumnIndex($colCol) . $rowCol)->setValue($list);
                                $colCol++;
                            }
                        }

                        $rowCol++;
                        $colCol = 0;
                    }
                });
                //                $freezeCol = config('custom.bidding.freeze_detail_col', 'B');
                //                $sheet->setFreeze($freezeCol . '2');
            });
            $excel->sheet('汇总表', function ($sheet) use ($request) {
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
                foreach ($biddinginformationdefinefields as $biddinginformationdefinefield) {
                    array_push($data, $biddinginformationdefinefield->name);
                }
                $sheet->appendRow($data);
                $rowCol = 2;        // 从第二行开始
                $colCol = 0;         //从第一列开始


                //                $query = DB::table('biddinginformations')->leftjoin('biddingprojects','biddinginformations.biddingprojectid','=','biddingprojects.id')->select('biddingprojects.name','biddinginformation.id')->get();

                Project_hxold::chunk(100, function ($biddingprojects) use ($sheet, $biddinginformationdefinefields, &$rowCol, &$colCol) {
                    foreach ($biddingprojects as $biddingproject) {
                        $data = [];

                        array_push($data, $biddingproject->name);
                        $sheet->getCell(\PHPExcel_Cell::stringFromColumnIndex($colCol) . $rowCol)->setValue($biddingproject->name);
                        $colCol++;
                        //                        dd($sheet->getCell(\PHPExcel_Cell::stringFromColumnIndex($colCol-1).$rowCol)->getValue());
                        $saleorderids = Salesorder_hxold::join('hxcrm2016.dbo.vproject', 'vproject.id', '=', 'project_id')
                            ->where('vproject.id', '=', $biddingproject->id)->pluck('vorder.id');
                        $query = Biddinginformation::latest('created_at');
                        $query->wherein('sohead_id', $saleorderids);
                        $biddinginformations = $query->select('biddinginformations.*')->get();
                        //                        dd();
                        if (count($biddinginformations) > 0) {
                            //                                dd($biddinginformation);
                            $biddinglist = '';
                            foreach ($biddinginformations as $biddinginformation) {
                                if ($biddinglist <> '') {
                                    $biddinglist = $biddinglist . ';' . $biddinginformation->number;
                                } else if ($biddinglist == '') {
                                    $biddinglist = $biddinginformation->number;
                                }
                            }
                            $sheet->getCell(\PHPExcel_Cell::stringFromColumnIndex($colCol) . $rowCol)->setValue($biddinglist);
                            $colCol++;

                            foreach ($biddinginformationdefinefields as $biddinginformationdefinefield) {
                                $list = '';
                                foreach ($biddinginformations as $biddinginformation) {
                                    //                                      $biddinginformationitem = $biddinginformation->biddinginformationitems()->where('key', $biddinginformationdefinefield->name)->first();
                                    $biddinginformationitem = Biddinginformationitem::where('biddinginformation_id', $biddinginformation->id)->where('key', $biddinginformationdefinefield->name)->select('key', 'value')->first();

                                    if (isset($biddinginformationitem) && $list <> '') {
                                        $list = $list . ';' . $biddinginformationitem->value;
                                    } else if (isset($biddinginformationitem) && $list == '') {
                                        $list = $biddinginformationitem->value;
                                    }
                                }
                                $sheet->getCell(\PHPExcel_Cell::stringFromColumnIndex($colCol) . $rowCol)->setValue($list);
                                $colCol++;
                            }
                        }

                        $rowCol++;
                        $colCol = 0;
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
