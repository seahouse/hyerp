<div class="form-group">
    {!! Form::label('approvaltype_id', '审批类别:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
    {!! Form::select('approvaltype_id', $approvaltypeList, null, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('approver_id', '审批人:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
    {!! Form::select('approver_id', $userList, null, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('level', '层级:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
    {!! Form::text('level', null, ['class' => 'form-control']) !!}
    </div>
</div>


<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary']) !!}
    </div>
</div>

