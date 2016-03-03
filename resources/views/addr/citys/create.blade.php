@extends('navbarerp')

@section('main')
    <h1>添加城市</h1>
    <hr/>
    
    {!! Form::open(['url' => 'addr/citys']) !!}
        @include('addr.citys._form', ['submitButtonText' => '添加'])
    {!! Form::close() !!}
    
    @include('errors.list')
@stop
