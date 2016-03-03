@extends('navbarerp')

@section('main')
    <h1>添加用户</h1>
    <hr/>
    
    {!! Form::open(['url' => 'system/users']) !!}
        @include('system.users._form', ['submitButtonText' => '添加'])
    {!! Form::close() !!}    


    
    @include('errors.list')
@stop
