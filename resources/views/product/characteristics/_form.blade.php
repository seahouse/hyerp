<div class="form-group">
    {!! Form::label('name', '名称:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>


<div class="form-group">
    {!! Form::label('descrip', '描述:') !!}
    {!! Form::text('descrip', null, ['class' => 'form-control']) !!}
</div>

{!! Form::hidden('bitems', $bitems, ['class' => 'form-control']) !!}

<div class="form-group">
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary form-control']) !!}
</div>
