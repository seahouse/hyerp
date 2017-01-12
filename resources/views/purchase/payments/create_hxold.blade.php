@extends('navbarerp')

@section('main')
    <h1>添加付款记录</h1>
    <hr/>
    
    {!! Form::open(['url' => 'purchase/purchaseorders/' . $purchaseorder->id . '/payments/store_hxold', 'class' => 'form-horizontal']) !!}
        @include('purchase.payments._form_hxold', ['submitButtonText' => '添加', 'amount' => '0.0', 'paydate' => date('Y-m-d')])
    {!! Form::close() !!}

    
    @include('errors.list')
@stop
