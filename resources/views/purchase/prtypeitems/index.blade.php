@extends('navbarerp')

@section('title', '采购申请单分组明细')

@section('main')
    <div class="panel-heading">
        @if ($prtype_id > 0)
            <a href="prtypeitems/create?prtype_id={{ $prtype_id }}" class="btn btn-sm btn-success">新建</a>
        @else
            <a href="prtypeitems/create" class="btn btn-sm btn-success">新建</a>
        @endif

{{--        <div class="pull-right" style="padding-top: 4px;">
            <a href="{{ URL::to('purchase/vendtypes') }}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> {{'客户类型管理', [], 'layouts'}}</a>
        </div> --}}
    </div>
    

    @if ($prtypeitems->count())
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>供应商</th>
                <th>物料</th>
                <th>数量</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prtypeitems as $prtypeitem)
                <tr>
                    <td>
                        {{ $prtypeitem->prtype->supplier->name }}
                    </td>
                    <td>
                        {{ $prtypeitem->item->goods_name }}
                    </td>
                    <td>
                        {{ $prtypeitem->quantity }}
                    </td>
                    <td>
                        <a href="{{ URL::to('/purchase/prtypeitems/'.$prtypeitem->id.'/edit') }}" class="btn btn-success btn-sm pull-left">编辑</a>
                        {!! Form::open(array('route' => array('purchase.prtypeitems.destroy', $prtypeitem->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                            {!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
    {!! $prtypeitems->render() !!}
    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{'无记录', [], 'layouts'}}
    </div>
    @endif    


@stop
