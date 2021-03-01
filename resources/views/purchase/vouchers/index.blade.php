@extends('navbarerp')

@section('main')


@if ($vouchers->count())
<table class="table table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th>编号</th>
            <th>名称</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @foreach($vouchers as $voucher)
        <tr>
            <td>
                {{ $voucher->number }}
            </td>
            <td>
                {{ $voucher->name }}
            </td>
            <td>
                <a href="{{ URL::to('/purchase/vouchers/'.$vendinfo->id.'/edit') }}" class="btn btn-success btn-sm pull-left">编辑</a>
                {!! Form::open(array('route' => array('purchase.vouchers.destroy', $vendinfo->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
                {!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}
                {!! Form::close() !!}
            </td>
        </tr>
        @endforeach
    </tbody>

</table>
{!! $vouchers->render() !!}
@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{'无记录', [], 'layouts'}}
</div>
@endif


@stop