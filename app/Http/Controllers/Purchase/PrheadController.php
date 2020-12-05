<?php

namespace App\Http\Controllers\Purchase;

use App\Models\Purchase\Prhead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Purchase\PrSupplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

        //        $prheads = Prhead::latest('created_at')->paginate(10);
        return view('purchase.prheads.index', compact('prheads', 'inputs'));
    }

    public function search(Request $request)
    {
        $inputs = $request->all();
        $prheads = $this->searchrequest($request)->paginate(15);

        return view('purchase.prheads.index', compact('prheads', 'inputs'));
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

        if ($request->has('key') && strlen($request->input('key')) > 0) {
            $query->where('number', 'like', '%' . $request->input('key') . '%');
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
}
