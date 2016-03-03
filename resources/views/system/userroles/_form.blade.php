<div class="form-group">
    {!! Form::label('username', '用户名称:') !!}
    {!! Form::text('username', $user->username, ['class' => 'form-control', 'readonly' => 'true']) !!}
</div>

<div class="form-group">
    {!! Form::label('name', '姓名:') !!}
    {!! Form::text('name', $user->name, ['class' => 'form-control', 'readonly' => 'true']) !!}
</div>


<div class="form-group">
    {!! Form::label('role_id', '角色:') !!}
    {!! Form::select('role_id', $roleList, null, ['class' => 'form-control']) !!}
</div>

{!! Form::hidden('user_id', $user->id, ['class' => 'form-control']) !!}

<div class="form-group">
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary form-control']) !!}
</div>
