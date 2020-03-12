{{--<h3><strong>Order Detail/订单:</strong></h3>--}}
{{--<hr />--}}
<div class="salary">

<div class="board">
    <div class="msg">{{ $salarysheet->username }}，工作辛苦啦</div>
    <div class="number">{{ $salarysheet->actualsalary_amount }}</div>
    <div class="bottom">实发工资</div>
</div>

<div class="form-group row">
    <div class='title col-xs-4 col-sm-2'>
        工资月份
    </div>
    <div class='content col-xs-8 col-sm-10'>
        {{ \Carbon\Carbon::parse($salarysheet->salary_date)->format('Y-m') }}
    </div>
</div>

<div class="form-group row">
    <div class='title col-xs-4 col-sm-2'>
        姓名
    </div>
    <div class='content col-xs-8 col-sm-10' >
        <?php echo $salarysheet->username?>
    </div>
</div>

<div class="form-group row">
    <div class='title col-xs-4 col-sm-2'>
        部门
    </div>
    <div class='content col-xs-8 col-sm-10'>
        <?php echo $salarysheet->department?>
    </div>
</div>

<div class="form-group row">
    <div class='title col-xs-4 col-sm-2'>
        出勤天数
    </div>
    <div class='content col-xs-8 col-sm-10'>
        <?php echo $salarysheet->attendance_days?>
    </div>
</div>

<div class="form-group row">
    <div class='title col-xs-4 col-sm-2'>
        基本工资
    </div>
    <div class='content col-xs-8 col-sm-10'>
        <?php echo $salarysheet->basicsalary?>
    </div>
</div>

@if (isset($salarysheet) && $salarysheet->overtime_hours != 0)
<div class="form-group row">
    <div class='title col-xs-4 col-sm-2'>
        加班小时
    </div>
    <div class='content col-xs-8 col-sm-10'>
        <?php echo $salarysheet->overtime_hours?>
    </div>
</div>
@endif

@if (isset($salarysheet) && $salarysheet->absenteeismreduce_hours != 0)
<div class="form-group row">
    <div class='title col-xs-4 col-sm-2'>
        缺勤减扣小时
    </div>
    <div class='content col-xs-8 col-sm-10' >
        <?php echo $salarysheet->absenteeismreduce_hours?>
    </div>
</div>
@endif

@if (isset($salarysheet) && $salarysheet->paid_hours != 0)
<div class="form-group row">
    <div class='title col-xs-4 col-sm-2'>
        计薪小时
    </div>
    <div class='content col-xs-8 col-sm-10' >
        <?php echo $salarysheet->paid_hours?>
    </div>
</div>
@endif

@if (isset($salarysheet) && $salarysheet->overtime_amount != 0)
<div class="form-group row">
    <div class='title col-xs-4 col-sm-2'>
        加班费
    </div>
    <div class='content col-xs-8 col-sm-10' >
        <?php echo $salarysheet->overtime_amount?>
    </div>
</div>
@endif

@if (isset($salarysheet) && $salarysheet->fullfrequently_award != 0)
<div class="form-group row">
    <div class='title col-xs-4 col-sm-2'>
        满勤奖
    </div>
    <div class='content col-xs-8 col-sm-10' >
        <?php echo $salarysheet->fullfrequently_award?>
    </div>
</div>
@endif

@if (isset($salarysheet) && $salarysheet->meal_amount != 0)
<div class="form-group row">
    <div class='title col-xs-4 col-sm-2'>
        餐贴
    </div>
    <div class='content col-xs-8 col-sm-10' >
        <?php echo $salarysheet->meal_amount?>
    </div>
</div>
@endif

@if (isset($salarysheet) && $salarysheet->car_amount != 0)
<div class="form-group row">
    <div class='title col-xs-4 col-sm-2'>
        车贴
    </div>
    <div class='content col-xs-8 col-sm-10' >
        <?php echo $salarysheet->car_amount?>
    </div>
</div>
@endif

@if (isset($salarysheet) && $salarysheet->business_amount != 0)
<div class="form-group row">
    <div class='title col-xs-4 col-sm-2'>
        外差补贴
    </div>
    <div class='content col-xs-8 col-sm-10' >
        <?php echo $salarysheet->business_amount?>
    </div>
</div>
@endif

@if (isset($salarysheet) && $salarysheet->additional_amount != 0)
<div class="form-group row">
    <div class='title col-xs-4 col-sm-2'>
        补资
    </div>
    <div class='content col-xs-8 col-sm-10' >
        <?php echo $salarysheet->additional_amount?>
    </div>
</div>
@endif

@if (isset($salarysheet) && $salarysheet->house_amount != 0)
<div class="form-group row">
    <div class='title col-xs-4 col-sm-2'>
        房贴
    </div>
    <div class='content col-xs-8 col-sm-10' >
        <?php echo $salarysheet->house_amount?>
    </div>
</div>
@endif

@if (isset($salarysheet) && $salarysheet->hightemperature_amount != 0)
<div class="form-group row">
    <div class='title col-xs-4 col-sm-2'>
        高温费
    </div>
    <div class='content col-xs-8 col-sm-10' >
        <?php echo $salarysheet->hightemperature_amount?>
    </div>
</div>
@endif


@if (isset($salarysheet) && $salarysheet->absenteeismreduce_amount != 0)
<div class="form-group row">
    <div class='title col-xs-4 col-sm-2'>
        缺勤扣款
    </div>
    <div class='content col-xs-8 col-sm-10' >
        <?php echo $salarysheet->absenteeismreduce_amount?>
    </div>
</div>
@endif

<div class="form-group row">
    <div class='title col-xs-4 col-sm-2'>
        应发工资
    </div>
    <div class='content col-xs-8 col-sm-10' >
        <?php echo $salarysheet->shouldpay_amount?>
    </div>
</div>

@if (isset($salarysheet) && $salarysheet->borrowreduce_amount != 0)
<div class="form-group row">
    <div class='title col-xs-4 col-sm-2'>
        借款扣回
    </div>
    <div class='content col-xs-8 col-sm-10' >
        <?php echo $salarysheet->borrowreduce_amount?>
    </div>
</div>
@endif

@if (isset($salarysheet) && $salarysheet->personalsocial_amount != 0)
<div class="form-group row">
    <div class='title col-xs-4 col-sm-2'>
        个人社保
    </div>
    <div class='content col-xs-8 col-sm-10' >
        <?php echo $salarysheet->personalsocial_amount?>
    </div>
</div>
@endif

@if (isset($salarysheet) && $salarysheet->personalaccumulationfund_amount != 0)
<div class="form-group row">
    <div class='title col-xs-4 col-sm-2'>
        个人公积金
    </div>
    <div class='content col-xs-8 col-sm-10' >
        <?php echo $salarysheet->personalaccumulationfund_amount?>
    </div>
</div>
@endif

@if (isset($salarysheet) && $salarysheet->individualincometax_amount != 0)
<div class="form-group row">
    <div class='title col-xs-4 col-sm-2'>
        个人所得税
    </div>
    <div class='content col-xs-8 col-sm-10' >
        <?php echo $salarysheet->individualincometax_amount?>
    </div>
</div>
@endif

<div class="form-group row">
    <div class='title col-xs-4 col-sm-2'>
        实发工资
    </div>
    <div class='content col-xs-8 col-sm-10' >
        <?php echo $salarysheet->actualsalary_amount?>
    </div>
</div>

<div class="form-group row">
    <div class='title col-xs-4 col-sm-2'>
        备注
    </div>
    <div class='content col-xs-8 col-sm-10' >
        {!! Form::textarea('remark', null, ['class' => 'form-control', 'rows' => 3, $attr]) !!}
    </div>
</div>














<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit($submitButtonText, ['class' => $btnclass, 'id' => 'btnSubmit']) !!}
    </div>
</div>

</div>
