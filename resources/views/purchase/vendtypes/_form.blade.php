<div class="form-group">
    {!! Form::label('code', '名称:') !!}
    {!! Form::text('code', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('descrip', '描述:') !!}
    {!! Form::text('descrip', null, ['class' => 'form-control']) !!}
</div>





<div class="form-group">
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary form-control']) !!}
</div>
