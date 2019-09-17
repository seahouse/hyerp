
<div class="form-group">
    {!! Form::label('name', '姓名:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>


<div class="form-group">
    {!! Form::label('email', '邮箱:') !!}
    {!! Form::input('email', 'email', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('dept_id', '部门:') !!}
    {!! Form::select('dept_id', $deptList, null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('position', '职位:') !!}
    {!! Form::text('position', null, ['class' => 'form-control', 'id' => 'position']) !!}
</div>

<div class="form-group">
    {!! Form::label('avatar', '头像:') !!}
    {!! Form::file('avatar') !!}
</div>



{{-- 
<div class="form-group">
    {!! Form::label('password', '密码:') !!}
    {!! Form::input('password', 'password', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('password', '确认密码:') !!}
    {!! Form::input('password', 'password_confirmation', null, ['class' => 'form-control']) !!}
</div>
--}}

<div class="form-group">
    {!! Form::label('dtuserid', '钉钉员工号:') !!}
    {!! Form::text('dtuserid', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary form-control']) !!}
</div>
