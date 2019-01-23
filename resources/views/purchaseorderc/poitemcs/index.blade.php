@extends('navbarerp')

@section('main')
    <div class="panel-heading">
        {{--
        <a href="{{ URL::to('purchaseorderc/poitemcs/' . $id . '/create') }}" class="btn btn-sm btn-success">新建</a>
         --}}
    </div>
    

    @if ($poitems->count())
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>面料序列号</th>
                <th>物料代码</th>
                <th>数量</th>
                <th>单位</th>
                <th>面料尺寸</th>
                <th>运输方式</th>
                <th>单价</th>
                <th>发货日期</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($poitems as $poitem)
                <tr>
                    <td>
                        {{ $poitem->fabric_sequence_no }}
                    </td>
                    <td>
                        {{ $poitem->material_code }}
                    </td>
                    <td>
                        {{ $poitem->quantity }}
                    </td>
                    <td>
                        {{ $poitem->unit }}
                    </td>
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
                    <td>
                        <a href="{{ URL::to('/purchaseorderc/poitemcs/'.$poitem->id.'/edit') }}" class="btn btn-success btn-sm pull-left">编辑</a>
                        {!! Form::open(array('route' => array('purchaseorderc.poitemcs.destroy', $poitem->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                            {!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
    {!! $poitems->render() !!}
    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{'无记录', [], 'layouts'}}
    </div>
    @endif    


@stop
