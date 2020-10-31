@extends('navbarerp')

@section('title', '采购申请单明细')

@section('main')
    <div class="panel-heading">
        {{--@if ($prhead_id > 0)--}}
            {{--<a href="pritems/create?prtype_id={{ $prhead_id }}" class="btn btn-sm btn-success">新建</a>--}}
        {{--@else--}}
            {{--<a href="pritems/create" class="btn btn-sm btn-success">新建</a>--}}
        {{--@endif--}}

{{--        <div class="pull-right" style="padding-top: 4px;">
            <a href="{{ URL::to('purchase/vendtypes') }}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> {{'客户类型管理', [], 'layouts'}}</a>
        </div> --}}
    </div>
    

    @if ($pritems->count())
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>申请单编号</th>
                <th>物料</th>
                <th>数量</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pritems as $pritem)
                <tr>
                    <td>
                        {{ $pritem->prhead->number }}
                    </td>
                    <td>
                        {{ $pritem->item->goods_name }}
                    </td>
                    <td>
                        {{ $pritem->quantity }}
                    </td>
                    <td>
                        {{--<a href="{{ URL::to('/purchase/prtypeitems/'.$pritem->id.'/edit') }}" class="btn btn-success btn-sm pull-left">编辑</a>--}}
                        {{--{!! Form::open(array('route' => array('purchase.prtypeitems.destroy', $pritem->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}--}}
                            {{--{!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}--}}
                        {{--{!! Form::close() !!}--}}
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
    {!! $pritems->render() !!}
    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{'无记录', [], 'layouts'}}
    </div>
    @endif    


@stop
