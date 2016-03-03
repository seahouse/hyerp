@extends('navbarerp')

@section('main')
    <h1>添加角色</h1>
    <hr/>
    
    {!! Form::open(['url' => 'system/roles']) !!}
        @include('system.roles._form', ['submitButtonText' => '添加'])
    {!! Form::close() !!}    


    
    @include('errors.list')
@stop
