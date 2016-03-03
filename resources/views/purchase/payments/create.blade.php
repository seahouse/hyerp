@extends('navbarerp')

@section('main')
    <h1>添加收款记录</h1>
    <hr/>
    
    {!! Form::open(['url' => 'purchase/purchaseorders/' . $purchaseorder->id . '/payments/store']) !!}
        @include('purchase.payments._form', ['submitButtonText' => '添加', 'amount' => '0.0', 'paydate' => date('Y-m-d')])
    {!! Form::close() !!}

    
    @include('errors.list')
@stop
