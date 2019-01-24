@extends('navbarerp')

@section('main')
    <div class="panel-heading">
        {{--
        <a href="{{ URL::to('purchaseorderc/poitemcs/' . $id . '/create') }}" class="btn btn-sm btn-success">新建</a>
         --}}
    </div>
    

    @if ($asnitems->count())
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>采购订单</th>
                <th>物料</th>
                <th>卷号</th>
                <th>数量</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($asnitems as $asnitem)
                <tr>
                    <td>
                        {{ $asnitem->poitemc->purchaseorderc->purchase_order_number }}
                    </td>
                    <td>
                        {{ $asnitem->poitemc->material_code }}
                    </td>
                    <td>
                        {{ $asnitem->roll_no }}
                    </td>
                    <td>
                        {{ $asnitem->quantity }}
                    </td>
                    {{--
                    <td>
                        {{ $poitem->fabric_width }}
                    </td>
                    <td>
                        {{ $poitem->transportation_method_type_code }}
                    </td>
                    <td>
                        {{ $poitem->unit_price }}
                    </td>
                    <td>
                        {{ $poitem->shipment_date }}
                    </td>
                    --}}
                    <td>
                        {{--
                        <a href="{{ URL::to('/purchaseorderc/poitemcs/'.$asnitem->id.'/edit') }}" class="btn btn-success btn-sm pull-left">编辑</a>
                        {!! Form::open(array('route' => array('purchaseorderc.poitemcs.destroy', $asnitem->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                            {!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}
                        {!! Form::close() !!}
                        --}}
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
    {!! $asnitems->render() !!}
    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{'无记录', [], 'layouts'}}
    </div>
    @endif    


@stop
