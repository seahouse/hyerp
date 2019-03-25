
<div class="form-group">
    {!! Form::label('create_time', '创建时间:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
    {!! Form::text('create_time', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('creator_name', '发起人:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
    {!! Form::text('creator_name', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('dept_name', '部门:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('dept_name', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('remark', '备注:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::textarea('remark', null, ['class' => 'form-control', $attr, 'rows' => 3]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('template_name', '日志模板:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('template_name', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

{{--<div class="form-group">--}}
    {{--<div class="col-sm-offset-2 col-sm-10">--}}
    {{--{!! Form::submit($submitButtonText, ['class' => 'btn btn-primary']) !!}--}}
    {{--</div>--}}
{{--</div>--}}

