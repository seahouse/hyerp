@extends('navbarerp')

@section('main')
    <div class="panel-heading">
{{--
        <a href="warehouses/create" class="btn btn-sm btn-success">新建</a>
        <div class="pull-right" style="padding-top: 4px;">
        </div>
--}}
    </div>
    

    @if ($receiptitems->count())
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>货品名称</th>
                <th>数量</th>
                @can('purchase_purchaseorder_viewamount')
                    <th>单价</th>
                    <th>合计金额</th>
                @endcan
                <th>录入日期</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($receiptitems as $receiptitem)
                <tr>
                    <td>
                        {{ $receiptitem->item->goods_name }}
                    </td>
                    <td>
                        {{ $receiptitem->quantity }}
                    </td>
                    @can('purchase_purchaseorder_viewamount')
                        <td>
                            {{ $receiptitem->unitprice }}
                        </td>
                        <td>
                            {{ $receiptitem->amount }}
                        </td>
                    @endcan
                    <td>
                        {{ $receiptitem->record_at }}
                    </td>
                    <td>
{{--
                        <a href="{{ URL::to('/inventory/warehouses/'.$warehouse->id.'/edit') }}" class="btn btn-success btn-mini pull-left">编辑</a>
                        {!! Form::open(array('route' => array('inventory.warehouses.destroy', $warehouse->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                            {!! Form::submit('删除', ['class' => 'btn btn-danger']) !!}
                        {!! Form::close() !!}
--}}
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>

    {!! $receiptitems->render() !!}

    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{'无记录', [], 'layouts'}}
    </div>
    @endif    

@stop
