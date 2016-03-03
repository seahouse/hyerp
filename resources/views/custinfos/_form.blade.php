<div class="form-group">
    {!! Form::label('number', '编号:') !!}
    {!! Form::text('number', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('name', '名称:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('contact_id', '联系人:') !!}
    {!! Form::select('contact_id', $contactList, null, ['class' => 'form-control']) !!}
</div>



<div class="form-group">
    {!! Form::label('comments', '说明:') !!}
    {!! Form::text('comments', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary form-control']) !!}
</div>
