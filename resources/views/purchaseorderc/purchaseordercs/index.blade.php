@extends('navbarerp')

@section('title', '采购订单')

@section('main')
    <div class="panel-heading">
        {{--
        <a href="purchaseordercs/create" class="btn btn-sm btn-success">新建</a>
--}}
    </div>
    

    @if ($purchaseorders->count())
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>采购订单编号</th>
                <th>发送时间</th>
                <th>测试标记</th>
                <th>类型</th>
                <th>产品类型</th>
                <th>编织类型</th>
                <th>目的地</th>
                <th>供应商名称</th>
                <th>收货方</th>
                <th>物料</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchaseorders as $purchaseorder)
                <tr>
                    <td>
                        {{ $purchaseorder->purchase_order_number }}
                    </td>
                    <td>
                        {{ $purchaseorder->interchange_datetime }}
                    </td>
                    <td>
                        {{ $purchaseorder->test_indicator }}
                    </td>
                    <td>
                        {{ $purchaseorder->po_type }}
                    </td>
                    <td>
                        {{ $purchaseorder->product_type }}
                    </td>
                    <td>
                        {{ $purchaseorder->weave_type }}
                    </td>
                    <td>
                        {{ $purchaseorder->destination_country }}
                    </td>
                    <td>
                        {{ $purchaseorder->supplier_name }}
                    </td>
                    <td>
                        {{ $purchaseorder->ship_to }}
                    </td>
                    <td>
                        <a href="{{ URL::to('/purchaseorderc/purchaseordercs/' . $purchaseorder->id . '/detail') }}" target="_blank">明细</a>
                    </td>
                    <td>
                        <a href="{{ URL::to('/purchaseorderc/purchaseordercs/'.$purchaseorder->id.'/edit') }}" class="btn btn-success btn-sm pull-left">编辑</a>
                        <a href="{{ URL::to('/purchase/purchaseorders/' . $purchaseorder->id . '/receiving') }}" class="btn btn-success btn-sm pull-left">收货</a>
                        <a href="{{ URL::to('/purchase/purchaseorders/' . $purchaseorder->id . '/payments') }}" target="_blank" class="btn btn-success btn-sm pull-left">付款</a>
                        {!! Form::open(array('route' => array('purchase.purchaseorders.destroy', $purchaseorder->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                            {!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
    {!! $purchaseorders->render() !!}
    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{'无记录', [], 'layouts'}}
    </div>
    @endif    


@stop
