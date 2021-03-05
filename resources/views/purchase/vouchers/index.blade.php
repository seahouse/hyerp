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
            <th>金额</th>
            <th>到账日期</th>
            <th>创建人</th>
            <th>更新人</th>
            <th>创建日期</th>
            <th>修改日期</th>
            <th>备注</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @foreach($vouchers as $voucher)
        <tr>
            <td>{{ $voucher->voucher_no }}</td>
            <td>{{ $voucher->amount }}</td>
            <td>{{ $voucher->post_date }}</td>
            <td>{{ $voucher->creator_user->name }}</td>
            <td>{{ isset($voucher->updater_user)?$voucher->updater_user->name:'' }}</td>
            <td>{{ $voucher->created_at }}</td>
            <td>{{ $voucher->updated_at }}</td>
            <td>{{ $voucher->remark }}</td>
            <td>
                <a href="{{ URL::to('/purchase/purchaseorders/'. $voucher->ref_id . '/vouchers/' . $voucher->id . '/edit') }}" class="btn btn-success btn-sm pull-left">编辑</a>

                <form action="{{ URL::to('/purchase/purchaseorders/'. $voucher->ref_id . '/vouchers/' . $voucher->id) }}" method="post" onsubmit='return confirm("确定删除此记录?");'>
                    {!! csrf_field() !!}
                    {!! method_field('delete') !!}

                    {!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}
                </form>
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