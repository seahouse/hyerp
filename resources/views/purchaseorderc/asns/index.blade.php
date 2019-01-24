@extends('navbarerp')

@section('title', 'ASN')

@section('main')
    <div class="panel-heading">
        {{--
        <a href="purchaseordercs/create" class="btn btn-sm btn-success">新建</a>
--}}
    </div>
    

    @if ($asns->count())
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>编号</th>
                {{--<th>发送时间</th>--}}
                {{--<th>测试标记</th>--}}
                {{--<th>类型</th>--}}
                {{--<th>产品类型</th>--}}
                {{--<th>编织类型</th>--}}
                {{--<th>目的地</th>--}}
                {{--<th>供应商名称</th>--}}
                <th>创建时间</th>
                <th>物料</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($asns as $asn)
                <tr>
                    <td>
                        {{ $asn->number }}
                    </td>
                    {{--<td>--}}
                        {{--{{ $purchaseorder->interchange_datetime }}--}}
                    {{--</td>--}}
                    {{--<td>--}}
                        {{--{{ $purchaseorder->test_indicator }}--}}
                    {{--</td>--}}
                    {{--<td>--}}
                        {{--{{ $purchaseorder->po_type }}--}}
                    {{--</td>--}}
                    {{--<td>--}}
                        {{--{{ $purchaseorder->product_type }}--}}
                    {{--</td>--}}
                    {{--<td>--}}
                        {{--{{ $purchaseorder->weave_type }}--}}
                    {{--</td>--}}
                    {{--<td>--}}
                        {{--{{ $purchaseorder->destination_country }}--}}
                    {{--</td>--}}
                    {{--<td>--}}
                        {{--{{ $purchaseorder->supplier_name }}--}}
                    {{--</td>--}}
                    <td>
                        {{ $asn->created_at }}
                    </td>
                    <td>
                        <a href="{{ URL::to('/purchaseorderc/asns/' . $asn->id . '/detail') }}" target="_blank">明细</a>
                    </td>
                    <td>
                        {{--
                        <a href="{{ URL::to('/purchaseorderc/purchaseordercs/'.$purchaseorder->id.'/edit') }}" class="btn btn-success btn-sm pull-left">编辑</a>
                        <a href="{{ URL::to('/purchaseorderc/purchaseordercs/' . $purchaseorder->id . '/packing') }}" class="btn btn-success btn-sm pull-left">打包</a>
                        --}}
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
    {!! $asns->render() !!}
    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{'无记录', [], 'layouts'}}
    </div>
    @endif    


@stop
