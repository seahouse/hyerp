@extends('navbarerp')

@section('main')
    <h1>编辑</h1>
    <hr/>
    
    {!! Form::model($user, ['method' => 'PATCH', 'action' => ['System\UsersController@update', $user->id]]) !!}
        @include('system.users._form_edit', ['submitButtonText' => '保存'])
    {!! Form::close() !!}
    
    @include('errors.list')
@stop

