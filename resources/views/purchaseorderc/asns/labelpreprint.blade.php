<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>ASN标签预览</title>

    <style>
        div.l1{
            width: 10cm;
            height: 15cm;
            border: 1px solid #000;
            background:transparent;
        }

        div.l2{
            margin-left: 0.8cm;
        }

        div.l21{
            width: 9cm;
            height: 12cm;
            border: 1px solid #000;
            background:transparent;
            margin-left: 0.5cm;
        }

        div.l3{
            margin-left: 0.2cm;
        }

        div.l31{
            margin-left: 3cm;
            width: 1px;
            height: 1cm;
            background: #000;
        }

        div.l41{
            /*margin-left: 3cm;*/
            width: 9cm;
            height: 1px;
            background: #000;
        }

        div.l5{
            margin-left: 0.2cm;
        }

        div.l51{
            width: 9cm;
            height: 1px;
            background: #000;
        }

        div.l6{
            margin-left: 0.2cm;
        }

        div.l61{
            width: 9cm;
            height: 1px;
            background: #000;
        }

        div.l7{
            margin-left: 0.2cm;
        }

        div.l8{
            margin-left: 0.2cm;
        }

        div.l10{
            margin-left: 0.2cm;
        }

        div.l10_1{
            width: 9cm;
            height: 1px;
            background: #000;
        }

        div.l13{
            margin-left: 0.2cm;
        }

        div.l16_1{
            width: 9cm;
            height: 1px;
            background: #000;
        }

        div.l18_1{
            width: 9cm;
            height: 1px;
            background: #000;
        }

        div.l20{
            margin-left: 0.5cm;
            margin-top: 0.2cm;
        }
    </style>

</head>
<body id="app-layout">
    <div class="l1">
        <div class="l2">TAL APPAREL LIMITED</div>
        <div class="l21">
            <div class="l3">PO#: @if ($asnitems->count() > 0) {{ $asnitems[0]->poitemc->purchaseorderc->purchase_order_number }} @endif</div>
            <div class="l41"></div>
            <div class="l5">Material Code: @if ($asnitems->count() > 0) {{ $asnitems[0]->poitemc->material_code }} @endif</div>
            <div class="l51"></div>
            <div class="l6">Ship From: @if ($asnitems->count() > 0) {{ $asnitems[0]->poitemc->purchaseorderc->supplier_name }} @endif</div>
            <div class="l61"></div>
            <div class="l7">Fabric: @if ($asnitems->count() > 0) {{ $asnitems[0]->poitemc->fabric_description }} @endif</div>
            <div class="l8">Color: @if ($asnitems->count() > 0) {{ $asnitems[0]->poitemc->color_desc1 }} @endif</div>
            <div class="l10">Roll #: @if ($asnitems->count() > 0) {{ $asnitems[0]->roll_no }} @endif</div>
            <div class="l10_1"></div>
            <div class="l12">{!! DNS1D::getBarcodeSVG("00123456789", "C128", 1.5, 50) !!}</div>
            <div class="l13">Qty: @if ($asnitems->count() > 0) {{ $asnitems[0]->quantity }} @endif</div>
            <div class="l16_1"></div>
            <div class="l17">Ship To: @if ($asnitems->count() > 0) {{ $asnitems[0]->poitemc->purchaseorderc->ship_to }} @endif</div>
            <div class="l18_1"></div>
            <div class="l19">Fabric Width: @if ($asnitems->count() > 0) {{ $asnitems[0]->poitemc->fabric_width }} @endif</div>
        </div>
        <div class="l20">{!! DNS1D::getBarcodeSVG("00123456789", "C128", 1.5, 50) !!}{!! DNS1D::getBarcodeSVG("00123456789", "C128", 1.5, 50) !!}</div>
    </div>

</body>
</html>
