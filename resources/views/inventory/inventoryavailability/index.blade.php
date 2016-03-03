@extends('navbarerp')

@section('main')
    <div class="panel-heading">
{{--        <a href="/sales/soitems/{{ $headId }}/create" class="btn btn-sm btn-success">新建</a>
       <div class="pull-right" style="padding-top: 4px;">
            <a href="{{ URL::to('sales/salesreps') }}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> {{'销售代表管理', [], 'layouts'}}</a>
            <a href="{{ URL::to('sales/terms') }}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> {{'付款条款管理', [], 'layouts'}}</a>
        </div> --}}
    </div>
    

    @if ($items->count())
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>订单编号</th>
                <th>物料编号</th>
                <th>订单数量</th>
                <th>已发货数量</th>
                <th>库存数量</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>
                        @if (isset($item->sohead->number)) {{ $item->sohead->number }} @endif
                    </td>
                    <td>
                        {{ $item->item->item_number }}
                    </td>
                    <td>
                        {{ $item->qty }}
                    </td>
                    <td>
                        {{ $item->qtyshipped }}
                    </td>
                    <td>
                        @if (isset($item->itemsite->qtyonhand)) {{ $item->itemsite->qtyonhand }} @else 0.0 @endif
                    </td>
{{--                    <td>
                        <a href="{{ URL::to('/sales/soitems/'.$soitem->id.'/edit') }}" class="btn btn-success btn-mini pull-left">编辑</a>
                        {!! Form::open(array('route' => array('sales.soitems.destroy', $soitem->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                            {!! Form::submit('删除', ['class' => 'btn btn-danger']) !!}
                        {!! Form::close() !!}
                    </td> --}}
                </tr>
            @endforeach
        </tbody>

    </table>
    {!! $items->render() !!}
    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{'无记录', [], 'layouts'}}
    </div>
    @endif    

@stop
