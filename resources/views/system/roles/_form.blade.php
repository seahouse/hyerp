<div class="form-group">
    {!! Form::label('name', '名称:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('display_name', '显示名称:') !!}
    {!! Form::text('display_name', null, ['class' => 'form-control']) !!}
</div>


<div class="form-group">
    {!! Form::label('description', '描述:') !!}
    {!! Form::text('description', null, ['class' => 'form-control']) !!}
</div>




<div class="form-group">
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary form-control']) !!}
</div>
