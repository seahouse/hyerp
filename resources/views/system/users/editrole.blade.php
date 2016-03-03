@extends('navbarerp')

@section('main')
    <h1>编辑</h1>
    <hr/>
    
    {!! Form::model($user, ['action' => ['System\UsersController@updaterole', $user->id]]) !!}
        <div class="form-group">
            {!! Form::label('username', '用户名称:') !!}
            {!! Form::text('username', null, ['class' => 'form-control', 'readonly' => 'true']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('role_id', '角色:') !!}
            {!! Form::select('role_id', $roleList, null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::submit('保存', ['class' => 'btn btn-primary form-control']) !!}
        </div>
    {!! Form::close() !!}
    
    @include('errors.list')
@stop

