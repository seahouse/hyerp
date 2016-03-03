@extends('navbarerp')

@section('main')
    <h1>添加销售代表</h1>
    <hr/>
    
    {!! Form::open(['url' => 'sales/salesreps']) !!}
        @include('sales.salesreps._form', ['submitButtonText' => '添加'])
    {!! Form::close() !!}
    

    
    @include('errors.list')
@stop
