@extends('app')

{!! Form::model($epcsecening, ['url' => 'approval/epcsecening/' . $epcsecening->id . '/updateamount',  'class' => 'form-horizontal']) !!}
<div class="form-group">
    {!! Form::label('amount_whl', '吴总定价:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('amount_whl', null, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit('保存', ['class' => 'btn btn-primary']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('business_id', '审批编号:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('business_id', null, ['class' => 'form-control', 'readonly']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('additional_design_department', '增补项所属设计部门:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('additional_design_department', null, ['class' => 'form-control', 'readonly']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('additional_source_department', '造成增补的责任归集部门:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('additional_source_department', null, ['class' => 'form-control', 'readonly']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('additional_reason_detaildesc', '增补原因详细说明:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::textarea('additional_reason_detaildesc', null, ['class' => 'form-control', 'readonly', 'rows' => 3]) !!}
    </div>
</div>
{!! Form::close() !!}

{!! Form::model($epcsecening, ['class' => 'form-horizontal']) !!}
@if (isset($epcsecening))
        @if ($epcsecening->epcseceningoptrecords->count())
            <p class="bannerTitle">操作记录</p>
        @endif
        @foreach ($epcsecening->epcseceningoptrecords as $epcseceningoptrecord)
            <div class="form-group">
                {!! Form::label('user_name', '操作人:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                <div class='col-sm-10 col-xs-8'>
                    {!! Form::text('user_name', isset($epcseceningoptrecord->operator) ? $epcseceningoptrecord->operator->name : null, ['class' => 'form-control', 'readonly']) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('date', '操作时间:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                <div class='col-sm-10 col-xs-8'>
                    {!! Form::text('date', \Carbon\Carbon::parse($epcseceningoptrecord->date)->toDateTimeString() , ['class' => 'form-control', 'readonly']) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('operation_type', '操作类型:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                <div class='col-sm-10 col-xs-8'>
                    {!! Form::text('operation_type', $epcseceningoptrecord->operation_type_zh(), ['class' => 'form-control', 'readonly']) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('operation_result', '操作结果:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                <div class='col-sm-10 col-xs-8'>
                    {!! Form::text('operation_result', $epcseceningoptrecord->operation_result_zh(), ['class' => 'form-control', 'readonly']) !!}
                </div>
            </div>

            @if (isset($epcseceningoptrecord->remark))
                <div class="form-group">
                    {!! Form::label('remark', '评论:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                    <div class='col-sm-10 col-xs-8'>
                        {!! Form::text('remark', $epcseceningoptrecord->remark, ['class' => 'form-control', 'readonly']) !!}
                    </div>
                </div>
            @endif
            <hr>
        @endforeach
@endif

{!! Form::close() !!}