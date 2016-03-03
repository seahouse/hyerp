@extends('navbarerp')

@section('main')
    <h1>添加供应商</h1>
    <hr/>
    
    {!! Form::open(['url' => 'purchase/vendinfos']) !!}
        @include('purchase.vendinfos._form', ['submitButtonText' => '添加'])
    {!! Form::close() !!}
    

    
    @include('errors.list')
@stop
