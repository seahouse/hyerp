@extends('navbarerp')

@section('main')
    <h1>添加收款记录</h1>
    <hr/>
    
    {!! Form::open(['url' => 'sales/salesorders/' . $salesorder->id . '/receivables/store']) !!}
        @include('sales.receivables._form', ['submitButtonText' => '添加', 'amount' => '0.0', 'recvdate' => date('Y-m-d')])
    {!! Form::close() !!}


    
    @include('errors.list')
@stop
