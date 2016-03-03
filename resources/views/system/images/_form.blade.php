<div class="form-group">
    {!! Form::label('name', '名称:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('descrip', '描述:') !!}
    {!! Form::text('descrip', null, ['class' => 'form-control']) !!}
</div>

{{--
<div class="form-group">
    {!! Form::label('path', '图片:') !!}
    {!! Form::text('path', '', ['class' => 'form-control']) !!}
</div>
--}}

<div class="form-group">
    {!! Form::label('image', '图片:') !!}
    {!! Form::file('image') !!}
</div>

<div class="form-group">
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary form-control']) !!}
</div>
