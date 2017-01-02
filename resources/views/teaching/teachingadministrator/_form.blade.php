<div class="form-group">
    {!! Form::label('user_id', '用户:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
    {!! Form::select('user_id', $userList, null, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('number', '编号:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
    {!! Form::text('number', null, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('teachingpoint_id', '教学点:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
    {!! Form::select('teachingpoint_id', $teachingpointList, null, ['class' => 'form-control']) !!}
    </div>
</div>

{{--
<div class="form-group">
    {!! Form::label('descrip', '描述:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
    {!! Form::text('descrip', null, ['class' => 'form-control']) !!}
    </div>
</div>
--}}






<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary']) !!}
    </div>
</div>





