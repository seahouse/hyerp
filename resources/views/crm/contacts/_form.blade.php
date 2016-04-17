<div class="form-group">
    {!! Form::label('name', '姓名:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('addr_id', '地址:') !!}
    {!! Form::select('addr_id', $addrList, null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('phone', '电话:') !!}
    {!! Form::text('phone', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('phone2', '电话2:') !!}
    {!! Form::text('phone2', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('fax', '传真:') !!}
    {!! Form::text('fax', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('email', '电子邮箱:') !!}
    {!! Form::text('email', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary form-control']) !!}
</div>
