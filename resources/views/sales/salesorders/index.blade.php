@extends('navbarerp')

@section('title', '销售订单')

@section('main')
    <div class="panel-heading">
        <div class="panel-title">销售 -- 销售订单
            <div class="pull-right">
                <a href="{{ URL::to('sales/salesreps') }}" class="btn btn-sm btn-success">{{'销售代表管理'}}</a>
                <a href="{{ URL::to('sales/terms') }}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> {{'付款条款管理', [], 'layouts'}}</a>
            </div>
        </div>
    </div>
    
    <div class="panel-body">
        <a href="{{ URL::to('/sales/salesorders/create') }}" class="btn btn-sm btn-success">新建</a>        
        <form class="pull-right" action="/sales/salesorders/search" method="post">
            {!! csrf_field() !!}
            <div class="pull-right">
                <button type="submit" class="btn btn-default btn-sm">查找</button>
            </div>
            <div class="pull-right input-group-sm">
                <input type="text" class="form-control" name="key" placeholder="Search">    
            </div>
        </form>
        
    </div>

    @if ($salesorders->count())
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>编号</th>
                <th>客户</th>
                <th>订单日期</th>
                <th>销售代表</th>
                <th>物料</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($salesorders as $salesorder)
                <tr>
                    <td>
                        {{ $salesorder->number }}
                    </td>
                    <td>
                        @if (isset($salesorder->custinfo->name)) {{ $salesorder->custinfo->name }} @endif
                    </td>
                    <td>
                        {{ $salesorder->orderdate }}
                    </td>
                    <td>
                        @if (isset($salesorder->salesrep->name)) {{ $salesorder->salesrep->name }} @endif
                    </td>
                    <td>
                        <a href="{{ URL::to('/sales/soitems/' . $salesorder->id . '/list') }}" target="_blank">明细</a>
                    </td>
                    <td>
                        <a href="{{ URL::to('/sales/salesorders/'.$salesorder->id.'/edit') }}" class="btn btn-success btn-sm pull-left">编辑</a>
                        <a href="{{ URL::to('/sales/salesorders/' . $salesorder->id . '/ship') }}" class="btn btn-success btn-sm pull-left">发货</a>
                        <a href="{{ URL::to('/sales/salesorders/' . $salesorder->id . '/receiptpayments') }}" target="_blank" class="btn btn-success btn-sm pull-left">收款</a>
                        {!! Form::open(array('route' => array('sales.salesorders.destroy', $salesorder->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                            {!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
    {!! $salesorders->render() !!}
    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{'无记录', [], 'layouts'}}
    </div>
    @endif    

@stop
