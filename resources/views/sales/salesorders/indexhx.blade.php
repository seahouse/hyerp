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
                <th>订单ID</th>
                <th>订单编号</th>
            </tr>
        </thead>
        <tbody>
            @foreach($salesorders as $salesorder)
                <tr>
                    <td>
                        {{ $salesorder->id }}
                    </td>
                    <td>
                        {{ $salesorder->number }}
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{'无记录', [], 'layouts'}}
    </div>
    @endif    

@stop
