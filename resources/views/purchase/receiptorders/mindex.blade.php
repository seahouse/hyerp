@extends('app')

@section('title', '采购订单')

@section('main')
<div class="panel-heading">
    <a href="purchaseorders/create" class="btn btn-sm btn-success">新建</a>
    {{-- <div class="pull-right" style="padding-top: 4px;">
            <a href="{{ URL::to('purchase/vendtypes') }}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> {{'客户类型管理', [], 'layouts'}}</a>
</div> --}}
</div>


@if ($receiptorders->count())
<table class="table table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th>入库单号</th>
            <th>入库时间</th>
            <th>仓库</th>
            <th>供货单位</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @foreach($receiptorders as $receiptorder)
        <tr>
            <td>
                <a href="{{ URL::to('/inventory/rwrecord/' . $receiptorder->receipt_id . '/receiptitems_hx') }}" target="_blank">{{ $receiptorder->rwrecord->number }}</a>
            </td>
            <td>
                {{ $receiptorder->rwrecord->create_at }}
            </td>
            <td>
                {{ $receiptorder->rwrecord->warehouse->name }}
            </td>
            <td>
                {{ $receiptorder->rwrecord->supplier->name }}
            </td>
            {{--
                    <td>
                        <a href="{{ URL::to('/purchase/purchaseorders/' . $purchaseorder->id . '/detail') }}" target="_blank">明细</a>
            </td>
            --}}
            <td>
                {{--
                        <a href="{{ URL::to('/purchase/purchaseorders/' . $purchaseorder->id . '/receiving') }}" class="btn btn-success btn-sm pull-left">收货</a>
                <a href="{{ URL::to('/purchase/purchaseorders/' . $purchaseorder->id . '/payments') }}" target="_blank" class="btn btn-success btn-sm pull-left">付款</a>
                {!! Form::open(array('route' => array('purchase.purchaseorders.destroy', $purchaseorder->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                {!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}
                {!! Form::close() !!}
                --}}
            </td>
        </tr>
        @endforeach
    </tbody>

</table>
{{--
    {!! $receiptorders->render() !!}
--}}
@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{'无记录', [], 'layouts'}}
</div>
@endif


@stop