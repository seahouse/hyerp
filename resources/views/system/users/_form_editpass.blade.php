


<div class="form-group">
    {!! Form::label('password', '密码:') !!}
    {!! Form::input('password', 'password', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('password', '确认密码:') !!}
    {!! Form::input('password', 'password_confirmation', null, ['class' => 'form-control']) !!}
</div>



<div class="form-group">
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary form-control']) !!}
</div>
