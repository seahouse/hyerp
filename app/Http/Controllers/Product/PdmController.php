<?php

namespace App\Http\Controllers\Product;

use App\Models\Product\k3\Icitem;
use App\Models\Product\k3\Item;
use App\Models\Product\Pdm\Cfdict;
use App\Models\Product\Pdm\Cffdr;
use App\Models\Product\Pdm\Cffdrref;
use App\Models\Product\Pdm\Cfobjkind;
use App\Models\Product\Pdmitem;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Log;

class PdmController extends Controller
{
    //
    public function index()
    {
        //
//         $items = Item::latest('created_at')->get();
//        $items = Item::latest('created_at')->paginate(10);
//         $itemclass = Item::find($id)->itemclass;
//         $items = Item::paginate(5);
        return view('product.pdms.index');
    }

    public function k3topdm()
    {
        $cffdr = Cffdr::where('fdrname', 'ERP导入')->firstOrFail();
        if (isset($cffdr))
        {
            Log::info('itemstopdm 1');
            $items = Icitem::orderBy('FItemID')->take(10)->get();
//            $items = Itemp_hxold::where('goods_no', '<>', '')->take(10)->get();
//            dd($items);
            foreach ($items as $item)
            {
                Log::info($item->FName);
                if (empty($item->FName)) continue;
//                if (empty($item->type_name) || empty($item->goods_no)) continue;
//                dd($item->FName);

                if ($item->FParentID > 0)
                {
                    $parentItem = Item::where('FItemID', $item->FParentID)->first();
                    if (isset($parentItem))
                    {
                        $typenumber = $parentItem->FNumber;
                        $typename = $parentItem->FName;
                        if (!empty($typename))
                        {
                            $subcffdr = Cffdr::where('fdrname', $typename)->first();
//                            dd($typename);
                            if (!isset($subcffdr))
                            {
                                $subcffdr = new Cffdr();
                                $fdrid = Cffdr::max('fdrid');
                                $fdrid += 1;
                                $subcffdr->fdrid = $fdrid;
                                $subcffdr->rev = 1;
                                $subcffdr->fdrname = $typename;
                                $subcffdr->stat = 1;
                                $subcffdr->code = $typenumber;
                                $subcffdr->actived = 2;
                                $subcffdr->type = 3;
                                $subcffdr->hide = 1;
                                $subcffdr->creator = 'hyerp';
                                $subcffdr->updator = 'hyerp';
                                $subcffdr->idpathdb = '-99\\' . $cffdr->fdrid . '\\' . $fdrid . '\\';
                                $subcffdr->typepathdb = '-1\\2\\2\\';
                                $subcffdr->namepathdb = '标准物料库\\ERP导入\\' . $typename . '\\';
                                $subcffdr->fdrkindid = 60;
                                $subcffdr->spobjid = $cffdr->fdrid;
                                $subcffdr->spobjtypeid = 2;
                                $subcffdr->revlabel = 'A.1';
                                $subcffdr->save();

                                $cffdrref = new Cffdrref();
                                $cffdrref->objid = $cffdr->fdrid;
                                $cffdrref->objrev = 1;
                                $cffdrref->refobjid = $fdrid;
                                $cffdrref->refobjrev = 1;
                                $cffdrref->refobjtypeid = 2;
                                $cffdrref->reftype = 1;
                                $cffdrref_seq = Cffdrref::where('objid', $cffdr->fdrid)->max('seq');
                                if (isset($cffdrref_seq))
                                    $cffdrref_seq += 1;
                                else
                                    $cffdrref_seq = 1;
                                $cffdrref->seq = $cffdrref_seq;
                                $cffdrref->fdrreftag=0;
                                $cffdrref->save();

                                dd($cffdrref);
                            }
                        }
                    }
                }
//                dd($item);

                $pdmitem = Pdmitem::where('itemcode', $item->FNumber)->first();
                if (!isset($pdmitem))
                {
                    $pdmitem = new Pdmitem();
                    $itemid = Pdmitem::max('itemid');
                    $itemid += 1;
                    $pdmitem->itemid = $itemid;
                    $pdmitem->rev = 1;
                    $pdmitem->revlabel = 'A.1';
                    $pdmitem->itemcode = $item->FNumber;
                    $pdmitem->itemname = $item->FName;
                    $pdmitem->spec = $item->FModel;
                    $pdmitem->itemtype = 4;             // 含义参考: select * from cfdict where pid=9 中的 dictvalue 字段, 原材料
//                    $unitname = $item->goods_unit_name;
//                    if (!empty($unitname))
//                    {
//                        $cfdict = Cfdict::where('pid', 19)->where('dictname', $unitname)->first();
//                        if (isset($cfdict))
//                        {
//                            $pdmitem->weightunit = $cfdict->dictvalue;
//                        }
//                    }
                    $pdmitem->stat = 1;             // select * from cfdict where pid=51
                    $pdmitem->actived = 2;
                    $pdmitem->creator = 'hyerp';
                    $pdmitem->updator = 'hyerp';
                    $cfobjkind = Cfobjkind::where('objkindname', '外购件')->first();
                    if (isset($cfobjkind))
                        $pdmitem->itemkindid = $cfobjkind->objkindid;
                    $pdmitem->idpathdb = '-99\\' . $cffdr->fdrid . '\\' . $subcffdr->fdrid . '\\' . $itemid . '\\';
                    $pdmitem->typepathdb = '-1\\2\\2\\21\\';
                    $pdmitem->namepathdb = '标准物料库\\ERP导入\\' . $typename . '\\' . $item->goods_name . '\\';
                    $pdmitem->spobjid = $subcffdr->fdrid;
                    $pdmitem->citemid = $itemid;
                    $pdmitem->save();

                    $cffdrref = new Cffdrref();
                    $cffdrref->objid = $subcffdr->fdrid;
                    $cffdrref->objrev = 0;
                    $cffdrref->refobjid = $itemid;
                    $cffdrref->refobjrev = 1;
                    $cffdrref->refobjtypeid = 21;
                    $cffdrref->reftype = 1;
                    $cffdrref_seq = Cffdrref::where('objid', $subcffdr->fdrid)->max('seq');
                    if (isset($cffdrref_seq))
                        $cffdrref_seq += 1;
                    else
                        $cffdrref_seq = 1;
                    $cffdrref->seq = $cffdrref_seq;
                    $cffdrref->fdrreftag=0;
                    $cffdrref->save();

                    dd($cffdrref);
                }
            }
        }


        dd('aaa');

        return view('product.items.sethxold2', compact('itemp', 'items2'));
    }
}
