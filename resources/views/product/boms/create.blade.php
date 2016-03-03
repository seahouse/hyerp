@extends('navbarerp')

@section('main')
    <h1>添加物料清单</h1>
    <hr/>
    
    {!! Form::open(['url' => '/boms']) !!}
        @include('product.boms._form', ['submitButtonText' => '添加'])
    {!! Form::close() !!}
    
    @include('errors.list')
@stop
