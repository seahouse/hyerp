@extends('navbarerp')

@section('main')
    <h1>编辑</h1>
    <hr/>
    
    {!! Form::model($role, ['method' => 'PATCH', 'action' => ['System\RolesController@update', $role->id]]) !!}
        @include('system.roles._form', ['submitButtonText' => '保存'])
    {!! Form::close() !!}
    
    @include('errors.list')
@stop

