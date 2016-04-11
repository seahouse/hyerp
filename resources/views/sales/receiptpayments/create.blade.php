@extends('navbarerp')

@section('title', '添加收款记录')

@section('main')
    <h1>添加收款记录</h1>
    <hr/>
    
    {!! Form::open(['url' => 'sales/salesorders/' . $salesorder->id . '/receiptpayments/store']) !!}
        @include('sales.receiptpayments._form', [
        		'submitButtonText' => '添加', 
        		'amount' => '0.0', 
        		'recvdate' => date('Y-m-d')
        	])
    {!! Form::close() !!}


    
    @include('errors.list')
@stop
