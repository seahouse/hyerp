@extends('navbarerp')

@section('main')
    <h1>设置老系统用户</h1>
    <hr/>
    
    {!! Form::model($user, ['method' => 'POST', 'action' => ['System\UsersController@updateuserold', $user->id], 'class' => 'form-horizontal']) !!}
        @include('system.users._form_edituserold', ['submitButtonText' => '保存'])
    {!! Form::close() !!}
    
    @include('errors.list')
@stop

