@extends('navbarerp')

@section('main')
    <h1>添加供应商类型</h1>
    <hr/>
    
    {!! Form::open(['url' => 'purchase/vendtypes']) !!}
        @include('purchase.vendtypes._form', ['submitButtonText' => '添加'])
    {!! Form::close() !!}
    

    
    @include('errors.list')
@stop
