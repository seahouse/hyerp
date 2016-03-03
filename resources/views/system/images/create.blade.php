@extends('navbarerp')

@section('main')
    <h1>添加图片</h1>
    <hr/>
    
    {!! Form::open(['url' => 'system/images', 'files' => true]) !!}
        @include('system.images._form', ['submitButtonText' => '添加'])
    {!! Form::close() !!}
    
    
@stop