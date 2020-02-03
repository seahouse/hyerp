{{--<h3><strong>Order Detail/订单:</strong></h3>--}}
{{--<hr />--}}

<div class="form-group">
    {!! Form::label('salary_date', '工资日期:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::date('salary_date', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('username', '姓名:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('username', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('department', '部门:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('department', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('attendance_days', '出勤天数:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('attendance_days', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('basicsalary', '基本工资:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('basicsalary', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

@if (isset($salarysheet) && $salarysheet->overtime_hours != 0)
<div class="form-group">
    {!! Form::label('overtime_hours', '加班小时:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('overtime_hours', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>
@endif

@if (isset($salarysheet) && $salarysheet->absenteeismreduce_hours != 0)
<div class="form-group">
    {!! Form::label('absenteeismreduce_hours', '缺勤减扣小时:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('absenteeismreduce_hours', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>
@endif

@if (isset($salarysheet) && $salarysheet->paid_hours != 0)
<div class="form-group">
    {!! Form::label('paid_hours', '计薪小时:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('paid_hours', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>
@endif

@if (isset($salarysheet) && $salarysheet->overtime_amount != 0)
<div class="form-group">
    {!! Form::label('overtime_amount', '加班费:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('overtime_amount', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>
@endif

@if (isset($salarysheet) && $salarysheet->fullfrequently_award != 0)
<div class="form-group">
    {!! Form::label('fullfrequently_award', '满勤奖:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('fullfrequently_award', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>
@endif

@if (isset($salarysheet) && $salarysheet->meal_amount != 0)
<div class="form-group">
    {!! Form::label('meal_amount', '餐贴:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('meal_amount', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>
@endif

@if (isset($salarysheet) && $salarysheet->car_amount != 0)
<div class="form-group">
    {!! Form::label('car_amount', '车贴:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('car_amount', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>
@endif

@if (isset($salarysheet) && $salarysheet->business_amount != 0)
<div class="form-group">
    {!! Form::label('business_amount', '外差补贴:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('business_amount', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>
@endif

@if (isset($salarysheet) && $salarysheet->additional_amount != 0)
<div class="form-group">
    {!! Form::label('additional_amount', '补资:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('additional_amount', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>
@endif

@if (isset($salarysheet) && $salarysheet->house_amount != 0)
<div class="form-group">
    {!! Form::label('house_amount', '房贴:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('house_amount', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>
@endif

@if (isset($salarysheet) && $salarysheet->hightemperature_amount != 0)
<div class="form-group">
    {!! Form::label('hightemperature_amount', '高温费:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('hightemperature_amount', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>
@endif


@if (isset($salarysheet) && $salarysheet->absenteeismreduce_amount != 0)
<div class="form-group">
    {!! Form::label('absenteeismreduce_amount', '缺勤扣款:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('absenteeismreduce_amount', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>
@endif

<div class="form-group">
    {!! Form::label('shouldpay_amount', '应发工资:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('shouldpay_amount', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

@if (isset($salarysheet) && $salarysheet->borrowreduce_amount != 0)
<div class="form-group">
    {!! Form::label('borrowreduce_amount', '借款扣回:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('borrowreduce_amount', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>
@endif

@if (isset($salarysheet) && $salarysheet->personalsocial_amount != 0)
<div class="form-group">
    {!! Form::label('personalsocial_amount', '个人社保:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('personalsocial_amount', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>
@endif

@if (isset($salarysheet) && $salarysheet->personalaccumulationfund_amount != 0)
<div class="form-group">
    {!! Form::label('personalaccumulationfund_amount', '个人公积金:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('personalaccumulationfund_amount', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>
@endif

@if (isset($salarysheet) && $salarysheet->individualincometax_amount != 0)
<div class="form-group">
    {!! Form::label('individualincometax_amount', '个人所得税:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('individualincometax_amount', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>
@endif

<div class="form-group">
    {!! Form::label('actualsalary_amount', '实发工资:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('actualsalary_amount', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('remark', '备注:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::textarea('remark', null, ['class' => 'form-control', 'rows' => 3, $attr]) !!}
    </div>
</div>














<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit($submitButtonText, ['class' => $btnclass, 'id' => 'btnSubmit']) !!}
    </div>
</div>

