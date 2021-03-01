@extends('navbarerp')

@section('main')
<h1>添加供应商扣款到账凭证</h1>
<hr />

{!! Form::open(['url' => 'purchase/vouchers']) !!}
@include('purchase.vouchers._form', ['submitButtonText' => '添加'])
{!! Form::close() !!}

@include('errors.list')
@stop