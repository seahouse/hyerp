<?php

namespace App\Http\Controllers\Purchase;

use App\Models\Purchase\Prhead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Approval\Mcitempurchase;
use App\Models\Approval\Techpurchase;
use App\Models\Purchase\PrSupplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class PrheadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $inputs = $request->all();
        $prheads = $this->searchrequest($request)->paginate(10);
        $purchase_types = Prhead::select('type')->distinct()->pluck('type', 'type');

        //        $prheads = Prhead::latest('created_at')->paginate(10);
        return view('purchase.prheads.index', compact('prheads', 'inputs', 'purchase_types'));
    }

    public function search(Request $request)
    {
        $inputs = $request->all();
        $prheads = $this->searchrequest($request)->paginate(15);
        $purchase_types = Prhead::select('type')->distinct()->pluck('type', 'type');

        return view('purchase.prheads.index', compact('prheads', 'inputs', 'purchase_types'));
    }

    public function searchrequest($request)
    {
        //        dd($request->all());
        $query = Prhead::latest('created_at');

        //        if ($request->has('createdatestart') && $request->has('createdateend'))
        //        {
        //            $query->whereRaw("DATEDIFF(DAY, create_time, '" . $request->input('createdatestart') . "') <= 0 and DATEDIFF(DAY, create_time, '" . $request->input('createdateend') . "') >=0");
        //
        //        }
        //
        //        if ($request->has('creator_name'))
        //        {
        //            $query->where('creator_name', $request->input('creator_name'));
        //        }
        // 根据采购类型查询
        if ($request->has('purchase_type') && strlen($request->input('purchase_type')) > 0) {
            $query->where('type', $request->input('purchase_type'));
        }
        // 根据审批单号查询
        if ($request->has('business_id') && strlen($request->input('business_id')) > 0) {
            $business_id = '%' . $request->input('business_id') . '%';

            $ids = Mcitempurchase::where('business_id', 'like', $business_id)->select('process_instance_id')
                ->union(Techpurchase::where('business_id', 'like', $business_id)->select('process_instance_id'))->get();

            $query->whereIn('process_instance_id', $ids);
        }
        if ($request->has('key') && strlen($request->input('key')) > 0) {
            $query->where('number', 'like', '%' . $request->input('key') . '%');
        }
        if ($request->has('projectname') && strlen($request->input('projectname')) > 0) {
            $query->whereHas('sohead', function ($q) use ($request) {
                //{{ $prhead->sohead->number . '!' . $prhead->sohead-> }}
                $tmp = "%{$request->input('projectname')}%";
                $q->where('number', 'like', $tmp)->orWhere('descrip', 'like', $tmp);
            });
        }
        if ($request->has('productname') && strlen($request->input('productname')) > 0) {
            $query->whereHas('pritems', function ($q) use ($request) {
                $q->whereHas('item', function ($q) use ($request) {
                    $q->where('goods_name', 'like', "%{$request->input('productname')}%");
                });
            });
        }
        if ($request->has('applicant') && strlen($request->input('applicant')) > 0) {
            $query->whereHas('applicant', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->input('applicant')}%");
            });
        }

        $user = Auth::user();
        if ($user->hasRole('supplier')) {
            $query->whereExists(function ($query) use ($user) {
                $query->select(DB::raw(1))
                    ->from('pr_suppliers')
                    ->whereRaw('pr_suppliers.prhead_id = prheads.id and pr_suppliers.supplier_id = ' . ($user->supplier_id ? $user->supplier_id : 0));
            });
        }

        return $query;

        //        $items = $query->select('prheads.*');

        //        return $items;
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
        $prhead = Prhead::findOrFail($id);
        $prhead->applicant_name = $prhead->applicant->name;
        $prhead->sohead_number = $prhead->sohead->number;
        $prhead->business_id = $prhead->associated_business_id();
        return view('purchase.prheads.edit', compact('prhead'));
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
        // dump($request->all());
        $chk_suppliers = $request->input('chk_suppliers');
        $suppliers = $request->input('suppliers');

        DB::transaction(function () use ($chk_suppliers, $suppliers, $id) {
            $prhead = Prhead::findOrFail($id);
            $prhead->suppliers()->delete();
            foreach ($suppliers as $key => $value) {
                PrSupplier::insert([
                    'prhead_id' => $id,
                    'supplier_id' => $value,
                    'selected' => $chk_suppliers[$key],
                ]);
            }
        });

        return redirect('purchase/prheads');
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
        Prhead::destroy($id);
        return redirect('purchase/prheads');
    }

    /**
     * 供应商报价页面
     * @param int $id
     */
    public function quote($id)
    {
        $prhead = Prhead::findOrFail($id);
        // 申请人
        $prhead->applicant_name = $prhead->applicant->name;
        // $prhead->sohead_number = $prhead->sohead->number;
        $prhead->business_id = $prhead->associated_business_id();

        $pr_supplier = $prhead->pr_supplier(Auth::user()->supplier_id);
        $prhead->amount = $pr_supplier ? $pr_supplier->amount : 0;
        return view('purchase.prheads.quote', compact('prhead'));
    }

    /**
     * 提交报价
     */
    public function updatequote(Request $request, $id)
    {
        $supplier_id = Auth::user()->supplier_id;
        $pr_supplier = PrSupplier::where('prhead_id', $id)->where('supplier_id', $supplier_id)->first();

        $amount = $request->get('amount');
        $destinationPath = "uploads/purchase/quote/{$id}/files/";
        Storage::deleteDirectory($destinationPath);
        $files = $request->file('files');
        // dump($files);
        $attachments = [];
        if ($files) {
            foreach ($files as $file) {
                if ($file) {
                    // $originalName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();       // .xlsx
                    $filename = date('YmdHis') . rand(100, 200) . '.' . $extension;
                    // Log::info($destinationPath . $originalName);
                    $name = $destinationPath . $filename;
                    Storage::put($name, file_get_contents($file->getRealPath()));

                    array_push($attachments, ['path' => $name]);
                }
            }
        }

        $pr_supplier->amount = $amount;
        $pr_supplier->attachments = json_encode($attachments);
        // dump($pr_supplier);
        // dd($attachments);
        $pr_supplier->save();
        return redirect('purchase/prheads');
    }

    /**
     * 导出询价表
     */
    public function export($id)
    {
        $prhead = Prhead::find($id);
        // $filename = $prhead->sohead->projectcj . '采购申请单';
        $filename = 'purchase_form';
        Excel::create($filename, function ($excel) use ($prhead) {
            $excel->sheet('清单', function ($sheet) use ($prhead) {
                $sheet->appendRow(['序号', '名称', '规格型号', '尺寸', '数量', '重量', '备注', '材质', '编号']);
                $row = 1;

                foreach ($prhead->pritems as $item) {
                    $sheet->appendRow([
                        $row,
                        $item->item->goods_name,
                        $item->item->goods_spec,
                        null,
                        $item->quantity,
                        null,
                        null,
                        null,
                        $item->prhead->number
                    ]);
                    $row++;
                }
            });
        })->store('xlsx', public_path('download/prhead'));
        $file = public_path('download/prhead/' . $filename . '.xlsx');
        Log::info('file path:' . $file);

        return response()->download($file);
    }
}
