<div class="form-group">
    {!! Form::label('code', '名称:') !!}
    {!! Form::text('code', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('descrip', '描述:') !!}
    {!! Form::text('descrip', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('duedays', '截止日:') !!}
    {!! Form::text('duedays', '0', ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('discdays', '贴现日:') !!}
    {!! Form::text('discdays', '0', ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('discprcnt', '贴现折扣:') !!}
    {!! Form::text('discprcnt', '0', ['class' => 'form-control']) !!}
</div>


<div class="form-group">
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary form-control']) !!}
</div>
