@extends('navbarerp')

@section('main')
    <h1>添加省份</h1>
    <hr/>
    
    {!! Form::open(['url' => '/addr/provinces']) !!}
        @include('addr.provinces._form', ['submitButtonText' => '添加'])
    {!! Form::close() !!}
    
    @include('errors.list')
@stop
