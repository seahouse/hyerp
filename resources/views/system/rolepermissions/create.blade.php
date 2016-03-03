@extends('navbarerp')

@section('main')
    <h1>添加角色权限</h1>
    <hr/>
    
    {!! Form::open(['url' => 'system/rolepermissions/store']) !!}
        @include('system.rolepermissions._form', ['submitButtonText' => '添加'])
    {!! Form::close() !!}    


    
    @include('errors.list')
@stop
