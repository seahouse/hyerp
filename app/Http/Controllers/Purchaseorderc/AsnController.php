<?php

namespace App\Http\Controllers\Purchaseorderc;

use App\Models\Purchaseorderc\Asn;
use App\Models\Purchaseorderc\Asnitem;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DNS1D, DNS2D;

class AsnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $asns = Asn::latest('created_at')->paginate(10);
        return view('purchaseorderc.asns.index', compact('asns'));
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

    public function packingstore(Request $request)
    {
        //
        $input = $request->all();
        if (strlen($input['items_string']) < 1)
            dd('未设置任何打包数据，保存ASN失败。');

        $number = Carbon::now()->toDateTimeString();
        $input['number'] = $number;

        $asn = Asn::create($input);

        if (isset($asn))
        {
            $asnitems = json_decode($input['items_string']);
            foreach ($asnitems as $asnitem) {
                $item_array = json_decode(json_encode($asnitem), true);
                $item_array['asn_id'] = $asn->id;

                Asnitem::create($item_array);
            }
        }
        return redirect('purchaseorderc/asns');
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

    public function detail($id)
    {
        $asnitems = Asnitem::where('asn_id', $id)->paginate(10);
        return view('purchaseorderc.asnitems.index', compact('asnitems', 'id'));
    }

    public function labelpreprint($id)
    {
//        echo DNS1D::getBarcodeSVG("4445645656", "PHARMA2T");
//        echo DNS1D::getBarcodeHTML("4445645656", "PHARMA2T");
//        echo '<img src="data:image/png,' . DNS1D::getBarcodePNG("4", "C39+") . '" alt="barcode"   />';
//        echo DNS1D::getBarcodePNGPath("4445645656", "PHARMA2T");

//        echo DNS1D::getBarcodeSVG("4445645656", "C39");
//        echo DNS2D::getBarcodeHTML("4445645656", "QRCODE");
//        echo DNS2D::getBarcodePNGPath("4445645656", "PDF417");
//        echo DNS2D::getBarcodeSVG("4445645656", "DATAMATRIX");
//        echo '<img src="data:image/png;base64,' . DNS2D::getBarcodePNG("4", "PDF417") . '" alt="barcode"   />';

        // Width and Height example
//        echo DNS1D::getBarcodeSVG("4445645656", "PHARMA2T",3,33);
//        echo DNS1D::getBarcodeHTML("4445645656", "PHARMA2T",3,33);
//        echo '<img src="' . DNS1D::getBarcodePNG("4", "C39+",3,33) . '" alt="barcode"   />';
//        echo DNS1D::getBarcodePNGPath("4445645656", "PHARMA2T",3,33);
//        echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG("4", "C39+",3,33) . '" alt="barcode"   />';

        // Color
//        echo DNS1D::getBarcodeSVG("4445645656", "PHARMA2T",3,33,"green");
//        echo DNS1D::getBarcodeHTML("4445645656", "PHARMA2T",3,33,"green");
//        echo '<img src="' . DNS1D::getBarcodePNG("4", "C39+",3,33,array(1,1,1)) . '" alt="barcode"   />';
//        echo DNS1D::getBarcodePNGPath("4445645656", "PHARMA2T",3,33,array(255,255,0));
//        echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG("4", "C39+",3,33,array(1,1,1)) . '" alt="barcode"   />';

        // Show Text
//        echo DNS1D::getBarcodeSVG("4445645656", "PHARMA2T",3,33,"green", true);
//        echo DNS1D::getBarcodeHTML("4445645656", "PHARMA2T",3,33,"green", true);
//        echo '<img src="' . DNS1D::getBarcodePNG("4", "C39+",3,33,array(1,1,1), true) . '" alt="barcode"   />';
//        echo DNS1D::getBarcodePNGPath("4445645656", "PHARMA2T",3,33,array(255,255,0), true);
//        echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG("4", "C39+",3,33,array(1,1,1), true) . '" alt="barcode"   />';
//        echo DNS1D::getBarcodeHTML("4445645656", "C128");

        // 2D Barcodes
//        echo DNS2D::getBarcodeHTML("4445645656", "QRCODE");
//        echo DNS2D::getBarcodePNGPath("4445645656", "PDF417");
//        echo DNS2D::getBarcodeSVG("4445645656", "DATAMATRIX");

        // 1D Barcodes
//        echo DNS1D::getBarcodeHTML("4445645656", "C39");
//        echo DNS1D::getBarcodeHTML("4445645656", "C39+");
//        echo DNS1D::getBarcodeHTML("4445645656", "C39E");
//        echo DNS1D::getBarcodeHTML("4445645656", "C39E+");
//        echo DNS1D::getBarcodeHTML("4445645656", "C93");
//        echo DNS1D::getBarcodeHTML("4445645656", "S25");
//        echo DNS1D::getBarcodeHTML("4445645656", "S25+");
//        echo DNS1D::getBarcodeHTML("4445645656", "I25");
//        echo DNS1D::getBarcodeHTML("4445645656", "I25+");
//        echo DNS1D::getBarcodeHTML("4445645656", "C128");
//        echo DNS1D::getBarcodeHTML("4445645656", "C128A");
//        echo DNS1D::getBarcodeHTML("4445645656", "C128B");
//        echo DNS1D::getBarcodeHTML("4445645656", "C128C");
//        echo DNS1D::getBarcodeHTML("44455656", "EAN2");
//        echo DNS1D::getBarcodeHTML("4445656", "EAN5");
//        echo DNS1D::getBarcodeHTML("4445", "EAN8");
//        echo DNS1D::getBarcodeHTML("4445", "EAN13");
//        echo DNS1D::getBarcodeHTML("4445645656", "UPCA");
//        echo DNS1D::getBarcodeHTML("4445645656", "UPCE");
//        echo DNS1D::getBarcodeHTML("4445645656", "MSI");
//        echo DNS1D::getBarcodeHTML("4445645656", "MSI+");
//        echo DNS1D::getBarcodeHTML("4445645656", "POSTNET");
//        echo DNS1D::getBarcodeHTML("4445645656", "PLANET");
//        echo DNS1D::getBarcodeHTML("4445645656", "RMS4CC");
//        echo DNS1D::getBarcodeHTML("4445645656", "KIX");
//        echo DNS1D::getBarcodeHTML("4445645656", "IMB");
//        echo DNS1D::getBarcodeHTML("4445645656", "CODABAR");
//        echo DNS1D::getBarcodeHTML("4445645656", "CODE11");
//        echo DNS1D::getBarcodeHTML("4445645656", "PHARMA");
//        echo DNS1D::getBarcodeHTML("4445645656", "PHARMA2T");

        $asn = Asn::findOrFail($id);
        $asnitems = Asnitem::where('asn_id', $id)->paginate(10);
        return view('purchaseorderc.asns.labelpreprint', compact('asn', 'asnitems', 'id'));
    }
}
