@extends('navbarerp')

@section('main')

<div class="panel-heading">
    @if (isset($purchaseorder->vendinfo))
    {{ $purchaseorder->vendinfo->name }}
    @else
    -
    @endif
</div>

@if ($vouchers->count())
<table class="table table-striped table-hover table-condensed">
    <thead>
        <tr>
            <th>凭证号</th>
            <th>凭证日期</th>
            <td>创建人</td>
            <td>更新人</td>
            <td>创建日期</td>
            <td>修改日期</td>
            <th>备注</th>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
        @foreach($vouchers as $voucher)
        <tr>
            <td>{{ $voucher->voucher_no }}</td>
            <td>{{ $voucher->post_date }}</td>
            <td>{{ $voucher->creator }}</td>
            <td>{{ $voucher->updater }}</td>
            <td>{{ $voucher->created_at }}</td>
            <td>{{ $voucher->updated_at }}</td>
            <td>{{ $voucher->remark }}</td>
            <td>
                <a href="{{ URL::to('/purchase/vouchers/'.$voucher->id.'/edit') }}" class="btn btn-success btn-sm pull-left">编辑</a>
                {!! Form::open(array('route' => array('purchase.vouchers.destroy', $voucher->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}
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