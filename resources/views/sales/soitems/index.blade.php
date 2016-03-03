@extends('navbarerp')

@section('main')
    <div class="panel-heading">
        <a href="/sales/soitems/{{ $headId }}/create" class="btn btn-sm btn-success">新建</a>
{{--       <div class="pull-right" style="padding-top: 4px;">
            <a href="{{ URL::to('sales/salesreps') }}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> {{'销售代表管理', [], 'layouts'}}</a>
            <a href="{{ URL::to('sales/terms') }}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> {{'付款条款管理', [], 'layouts'}}</a>
        </div> --}}
    </div>
    

    @if ($soitems->count())
    <table class="table table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th>物料编号</th>
                <th>数量</th>
                <th>单价</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($soitems as $soitem)
                <tr>
                    <td>
                        {{ $soitem->item->item_number }}
                    </td>
                    <td>
                        {{ $soitem->qty }}
                    </td>
                    <td>
                        {{ $soitem->price }}
                    </td>
                    <td>
                        <a href="{{ URL::to('/sales/soitems/'.$soitem->id.'/edit') }}" class="btn btn-success btn-sm pull-left">编辑</a>
                        {!! Form::open(array('route' => array('sales.soitems.destroy', $soitem->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                            {!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
    {!! $soitems->render() !!}
    @else
    <div class="alert alert-warning alert-block">
        <i class="fa fa-warning"></i>
        {{'无记录', [], 'layouts'}}
    </div>
    @endif    

@stop
