@extends('navbarerp')

@section('main')
    <h1>添加订单</h1>
    <hr/>
    
    {!! Form::open(['url' => 'sales/salesorders']) !!}
        @include('sales.salesorders._form', ['submitButtonText' => '添加', 'orderdate' => date('Y-m-d')])
    {!! Form::close() !!}   


    

    
    @include('errors.list')
@stop
