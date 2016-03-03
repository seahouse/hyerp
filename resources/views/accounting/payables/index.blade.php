@extends('navbarerp')

@section('main')
    <div class="panel-heading">
{{--        <a href="receiptpayments/create" class="btn btn-sm btn-success">新建</a>
       <div class="pull-right" style="padding-top: 4px;">
            <a href="{{ URL::to('system/roles') }}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> {{'角色管理', [], 'layouts'}}</a>
            <a href="{{ URL::to('system/images') }}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> {{'图片管理', [], 'layouts'}}</a> 
        </div>  --}}
    </div>
    

    @if ($payables->count())
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>供应商</th>
                <th>订单总金额</th>
                <th>累计已付</th>
                <th>应付余额</th>
{{--                <th>操作</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach($payables as $payable)
                <tr>
                    <td>
                        {{ $payable->number }}
                    </td>
                    <td>
                        {{ $payable->poitems->sum(function ($item) {
                            return $item['qty_ordered'] * $item['unitprice'];
                        }) }}
                    </td>
                    <td>
                         {{ $payable->payments->sum('amount') }}
                    </td>
                    <td>
                        {{ $payable->poitems->sum(function ($item) {
                            return $item['qty_ordered'] * $item['unitprice'];
                        }) - $payable->payments->sum('amount') }}
                    </td>
{{--                    <td>
                        <a href="{{ URL::to('/system/userroles/'.$userrole->id.'/edit') }}" class="btn btn-success btn-sm pull-left">编辑</a> 
                        {!! Form::open(array('url' => '/sales/salesorders/' . $receiptpayment->sohead_id . '/receiptpayments/destroy/' . $receiptpayment->id, 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                            {!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}
                        {!! Form::close() !!}  
                    </td> --}} 
                </tr>
            @endforeach
        </tbody>

    </table>
    {!! $payables->render() !!}
    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{'无记录', [], 'layouts'}}
    </div>
    @endif    

@stop
