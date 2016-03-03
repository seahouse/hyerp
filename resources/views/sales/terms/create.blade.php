@extends('navbarerp')

@section('main')
    <h1>添加付款条款</h1>
    <hr/>
    
    {!! Form::open(['url' => 'sales/terms']) !!}
        @include('sales.terms._form', ['submitButtonText' => '添加'])
    {!! Form::close() !!}
    

    
    @include('errors.list')
@stop
