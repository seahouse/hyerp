@extends('navbarerp')

@section('main')
    <h1>添加采购物料</h1>
    <hr/>
    
    {!! Form::open(['url' => 'purchase/poitems']) !!}
        @include('purchase.poitems._form', ['submitButtonText' => '添加'])
    {!! Form::close() !!}    

    
    @include('errors.list')
@stop
