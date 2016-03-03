@extends('navbarerp')

@section('main')
    <h2>物料 -- 添加物料</h2>
    <hr/>
    
    {!! Form::open(array('url' => 'product/items', 'class' => 'form-horizontal')) !!}
        @include('product.items._form', ['submitButtonText' => '添加', 'marketprice' => '0.0'])
    {!! Form::close() !!}
    
    @include('errors.list')
@stop
    

