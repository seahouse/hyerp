@extends('navbarerp')

@section('main')
    <h1>添加用户角色</h1>
    <hr/>
    
    {!! Form::open(['url' => 'system/userroles/store']) !!}
        @include('system.userroles._form', ['submitButtonText' => '添加'])
    {!! Form::close() !!}    


    
    @include('errors.list')
@stop
