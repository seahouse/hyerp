@extends('navbarerp')

@section('main')
<h1>编辑</h1>
<hr />

{!! Form::model($voucher, ['method' => 'PATCH', 'action' => ['Purchase\VoucherController@update', $voucher->ref_id, $voucher->id]]) !!}
@include('purchase.vouchers._form', ['submitButtonText' => '保存'])
{!! Form::close() !!}

@include('errors.list')
@stop