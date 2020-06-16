
{{--<div class="form-group">--}}
    {{--{!! Form::label('dept_name', '部门:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}--}}
    {{--<div class='col-xs-8 col-sm-10'>--}}
        {{--{!! Form::text('dept_name', null, ['class' => 'form-control', $attr]) !!}--}}
    {{--</div>--}}
{{--</div>--}}

<div class="form-group">
    {!! Form::label('remark', '备注:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::textarea('remark', null, ['class' => 'form-control', $attr, 'rows' => 3]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('sohead_id', '关联销售订单:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('sohead_id', isset($biddinginformation->sohead)? $biddinginformation->sohead->number : null, ['class' => 'form-control', $attr,'data-toggle' => 'modal', 'data-target' => '#selectOrderModal','data-informationid' =>$biddinginformation->id]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('projectid', '所属项目:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('projectid', isset($biddinginformation->biddingproject)? $biddinginformation->biddingproject->name : null, ['class' => 'form-control', $attr,'data-toggle' => 'modal', 'data-target' => '#selectBiddingprojectModal','data-informationid' =>$biddinginformation->id]) !!}
    </div>
    {!! Form::hidden('biddingprojectid', 0, ['class' => 'form-control', 'id' => 'biddingprojectid']) !!}
</div>
{{--<div class="form-group">--}}
    {{--{!! Form::label('template_name', '日志模板:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}--}}
    {{--<div class='col-xs-8 col-sm-10'>--}}
        {{--{!! Form::text('template_name', null, ['class' => 'form-control', $attr]) !!}--}}
    {{--</div>--}}
{{--</div>--}}

{{--<div class="form-group">--}}
    {{--<div class="col-sm-offset-2 col-sm-10">--}}
    {{--{!! Form::submit($submitButtonText, ['class' => 'btn btn-primary']) !!}--}}
    {{--</div>--}}
{{--</div>--}}
