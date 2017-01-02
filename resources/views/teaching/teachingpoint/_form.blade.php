<div class="form-group">
    {!! Form::label('name', '名称:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
    </div>
</div>


<div class="form-group">
    {!! Form::label('descrip', '描述:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
    {!! Form::text('descrip', null, ['class' => 'form-control']) !!}
    </div>
</div>








<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary']) !!}
    </div>
</div>






