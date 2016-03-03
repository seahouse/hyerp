@extends('navbarerp')

@section('main')
    <h1>添加物料类别</h1>
    <hr/>
    
    {!! Form::open(['url' => '/product/itemclasses', 'class' => 'form-horizontal']) !!}
        @include('product.itemclasses._form', ['submitButtonText' => '添加'])
    {!! Form::close() !!}
    
    @include('errors.list')
@stop
