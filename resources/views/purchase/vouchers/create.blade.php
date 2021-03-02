@extends('navbarerp')

@section('main')
<h1>添加供应商扣款到账凭证</h1>
<hr />

<form action="{{ '/purchase/purchaseorders/' . $purchaseorder->id . '/vouchers' }}" method="post">
    {!! csrf_field() !!}

    @include('purchase.vouchers._form', ['submitButtonText' => '添加'])
</form>

@include('errors.list')
@stop