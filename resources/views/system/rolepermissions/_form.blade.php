<div class="form-group">
    {!! Form::label('rolename', '角色名称:') !!}
    {!! Form::text('rolename', $role->name, ['class' => 'form-control', 'readonly' => 'true']) !!}
</div>

<div class="form-group">
    {!! Form::label('display_name', '角色显示名称:') !!}
    {!! Form::text('display_name', $role->display_name, ['class' => 'form-control', 'readonly' => 'true']) !!}
</div>


<div class="form-group">
    {!! Form::label('permission_id', '权限:') !!}
    {!! Form::select('permission_id', $permissionList, null, ['class' => 'form-control']) !!}
</div>

{!! Form::hidden('role_id', $role->id, ['class' => 'form-control']) !!}

<div class="form-group">
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary form-control']) !!}
</div>
