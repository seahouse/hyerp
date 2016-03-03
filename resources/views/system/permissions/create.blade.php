@extends('navbarerp')

@section('main')
    <h1>添加权限</h1>
    <hr/>
    
    {!! Form::open(['url' => 'system/permissions']) !!}
        @include('system.permissions._form', ['submitButtonText' => '添加'])
    {!! Form::close() !!}    


    
    @include('errors.list')
@stop
