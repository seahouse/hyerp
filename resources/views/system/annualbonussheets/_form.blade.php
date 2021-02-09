<div class="salary">

    <div class="board">
        <div class="msg">{{ $annualbonussheet->username }}，工作辛苦啦</div>
        <div class="number">{{ $annualbonussheet->actual_amount }}</div>
        <div class="bottom">实际发放</div>
    </div>

<div class="form-group row">
    <div class='title col-xs-4 col-sm-2'>
        奖金日期
    </div>
    <div class='content col-xs-8 col-sm-10'>
        {{ $annualbonussheet->salary_date }}
    </div>
</div>

    <div class="form-group row">
        <div class='title col-xs-4 col-sm-2'>
            姓名
        </div>
        <div class='content col-xs-8 col-sm-10'>
            {{ $annualbonussheet->username }}
        </div>
    </div>

    <div class="form-group row">
        <div class='title col-xs-4 col-sm-2'>
            部门
        </div>
        <div class='content col-xs-8 col-sm-10'>
            {{ $annualbonussheet->department }}
        </div>
    </div>



@if (isset($annualbonussheet) && $annualbonussheet->salaryincrease != 0)
        <div class="form-group row">
            <div class='title col-xs-4 col-sm-2'>
                增长工资
            </div>
            <div class='content col-xs-8 col-sm-10'>
                {{ $annualbonussheet->salaryincrease }}
            </div>
        </div>
@endif

    <div class="form-group row">
        <div class='title col-xs-4 col-sm-2'>
            月份
        </div>
        <div class='content col-xs-8 col-sm-10'>
            {{ $annualbonussheet->months }}
        </div>
    </div>


@if (isset($annualbonussheet) && $annualbonussheet->yearend_salary != 0)
        <div class="form-group row">
            <div class='title col-xs-4 col-sm-2'>
                年终工资
            </div>
            <div class='content col-xs-8 col-sm-10'>
                {{ $annualbonussheet->yearend_salary }}
            </div>
        </div>
@endif

@if (isset($annualbonussheet) && $annualbonussheet->performance_salary != 0)
        <div class="form-group row">
            <div class='title col-xs-4 col-sm-2'>
                绩效工资
            </div>
            <div class='content col-xs-8 col-sm-10'>
                {{ $annualbonussheet->performance_salary }}
            </div>
        </div>
@endif

@if (isset($annualbonussheet) && $annualbonussheet->yearend_bonus != 0)
        <div class="form-group row">
            <div class='title col-xs-4 col-sm-2'>
                年终奖金
            </div>
            <div class='content col-xs-8 col-sm-10'>
                {{ $annualbonussheet->yearend_bonus }}
            </div>
        </div>
@endif

@if (isset($annualbonussheet) && $annualbonussheet->duty_subsidy != 0)
        <div class="form-group row">
            <div class='title col-xs-4 col-sm-2'>
                职务补贴
            </div>
            <div class='content col-xs-8 col-sm-10'>
                {{ $annualbonussheet->duty_subsidy }}
            </div>
        </div>
@endif

@if (isset($annualbonussheet) && $annualbonussheet->duty_allowance != 0)
        <div class="form-group row">
            <div class='title col-xs-4 col-sm-2'>
                职称津贴
            </div>
            <div class='content col-xs-8 col-sm-10'>
                {{ $annualbonussheet->duty_allowance }}
            </div>
        </div>
@endif

@if (isset($annualbonussheet) && $annualbonussheet->forum_amount != 0)
        <div class="form-group row">
            <div class='title col-xs-4 col-sm-2'>
                座谈会
            </div>
            <div class='content col-xs-8 col-sm-10'>
                {{ $annualbonussheet->forum_amount }}
            </div>
        </div>
@endif

@if (isset($annualbonussheet) && $annualbonussheet->other_amount != 0)
        <div class="form-group row">
            <div class='title col-xs-4 col-sm-2'>
                其他
            </div>
            <div class='content col-xs-8 col-sm-10'>
                {{ $annualbonussheet->other_amount }}
            </div>
        </div>
@endif

@if (isset($annualbonussheet) && $annualbonussheet->boss_prize != 0)
        <div class="form-group row">
            <div class='title col-xs-4 col-sm-2'>
                老板奖
            </div>
            <div class='content col-xs-8 col-sm-10'>
                {{ $annualbonussheet->boss_prize }}
            </div>
        </div>
@endif

@if (isset($annualbonussheet) && $annualbonussheet->amount != 0)
        <div class="form-group row">
            <div class='title col-xs-4 col-sm-2'>
                发放金额
            </div>
            <div class='content col-xs-8 col-sm-10'>
                {{ $annualbonussheet->amount }}
            </div>
        </div>
@endif

@if (isset($annualbonussheet) && $annualbonussheet->goodemployee_amount != 0)
        <div class="form-group row">
            <div class='title col-xs-4 col-sm-2'>
                优秀员工
            </div>
            <div class='content col-xs-8 col-sm-10'>
                {{ $annualbonussheet->goodemployee_amount }}
            </div>
        </div>
@endif

@if (isset($annualbonussheet) && $annualbonussheet->totalamount != 0)
        <div class="form-group row">
            <div class='title col-xs-4 col-sm-2'>
                合计
            </div>
            <div class='content col-xs-8 col-sm-10'>
                {{ $annualbonussheet->totalamount }}
            </div>
        </div>
@endif

@if (isset($annualbonussheet) && $annualbonussheet->borrow_wages != 0)
        <div class="form-group row">
            <div class='title col-xs-4 col-sm-2'>
                借款扣回
            </div>
            <div class='content col-xs-8 col-sm-10'>
                {{ $annualbonussheet->borrow_wages }}
            </div>
        </div>
@endif

@if (isset($annualbonussheet) && $annualbonussheet->individualincometax_amount != 0)
        <div class="form-group row">
            <div class='title col-xs-4 col-sm-2'>
                个税
            </div>
            <div class='content col-xs-8 col-sm-10'>
                {{ $annualbonussheet->individualincometax_amount }}
            </div>
        </div>
@endif


@if (isset($annualbonussheet) && $annualbonussheet->actual_amount != 0)
        <div class="form-group row">
            <div class='title col-xs-4 col-sm-2'>
                实际发放
            </div>
            <div class='content col-xs-8 col-sm-10'>
                {{ $annualbonussheet->actual_amount }}
            </div>
        </div>
@endif


    <div class="form-group row">
        <div class='title col-xs-4 col-sm-2'>
            备注
        </div>
        <div class='content col-xs-8 col-sm-10'>
            {!! Form::textarea('remark', null, ['class' => 'form-control', 'rows' => 3, $attr]) !!}
        </div>
    </div>
    















<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit($submitButtonText, ['class' => $btnclass, 'id' => 'btnSubmit']) !!}
    </div>
</div>

</div>