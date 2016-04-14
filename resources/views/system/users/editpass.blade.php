@extends('navbarerp')

@section('main')
    <h1>编辑</h1>
    <hr/>
    
    {!! Form::model($user, ['method' => 'POST', 'action' => ['System\UsersController@updatepass', $user->id]]) !!}
        @include('system.users._form_editpass', ['submitButtonText' => '保存'])
    {!! Form::close() !!}
    
    @include('errors.list')
@stop

