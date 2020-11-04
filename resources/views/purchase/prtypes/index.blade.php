@extends('navbarerp')

@section('title', '采购申请单分组')

@section('main')
    <div class="panel-heading">
        @if ($prhead_id > 0)
            <a href="prtypes/create?prhead_id={{ $prhead_id }}" class="btn btn-sm btn-success">新建</a>
        @else
            <a href="prtypes/create" class="btn btn-sm btn-success">新建</a>
        @endif

{{--        <div class="pull-right" style="padding-top: 4px;">
            <a href="{{ URL::to('purchase/vendtypes') }}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> {{'客户类型管理', [], 'layouts'}}</a>
        </div> --}}
    </div>
    

    @if ($prtypes->count())
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>采购申请单编号</th>
                <th>供应商</th>
                <th>报价</th>
                <th>商品</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prtypes as $prtype)
                <tr>
                    <td>
                        {{ $prtype->prhead->number }}
                    </td>
                    <td>
                        {{ $prtype->supplier->name }}
                    </td>
                    <td>
                        {{ $prtype->quoteamount }}
                    </td>
                    <td>
                        <a href="{{ URL::to('/purchase/prtypeitems/') . '?prtype_id=' . $prtype->id }}" target="_blank">明细</a>
                    </td>
                    <td>
                        <a href="{{ URL::to('/purchase/prtypes/'.$prtype->id.'/edit') }}" class="btn btn-success btn-sm pull-left">编辑</a>
                        {!! Form::open(array('route' => array('purchase.prtypes.destroy', $prtype->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                            {!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
    {!! $prtypes->render() !!}
    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{'无记录', [], 'layouts'}}
    </div>
    @endif    


@stop
