@extends('navbarerp')

@section('main')
    <h1>物料 - 添加物料属性</h1>
    <hr/>
    
    {!! Form::open(['url' => 'product/characteristics']) !!}
        @include('product.characteristics._form', ['submitButtonText' => '添加', 'bitems' => true])
    {!! Form::close() !!}

    
    @include('errors.list')
@stop
