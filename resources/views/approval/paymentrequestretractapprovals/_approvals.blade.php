@if (isset($paymentrequestretract))
	<div class="reimb">
        <p class="bannerTitle">撤回审批记录</p>
        <div class="form-group">
            {!! Form::label('retractreason', '撤回原因:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-sm-10 col-xs-8'>
                {!! Form::text('retractreason', null, ['class' => 'form-control', $attr]) !!}
            </div>
        </div>

    @foreach ($paymentrequestretract->paymentrequestretractapprovals as $paymentrequestretractapproval)

    <div class="form-group">
        {!! Form::label('approver', '审批人:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
        <div class='col-sm-10 col-xs-8'>
        {!! Form::text('approver', $paymentrequestretractapproval->approver->name, ['class' => 'form-control', $attr]) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('status', '审批结果:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
        <div class='col-sm-10 col-xs-8'>
        @if ($paymentrequestretractapproval->status==0)
        {!! Form::text('status', '通过', ['class' => 'form-control', $attr]) !!}
        @else
        {!! Form::text('status', '未通过', ['class' => 'form-control', $attr]) !!}
        @endif
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('description', '审批描述:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
        <div class='col-sm-10 col-xs-8'>
        {!! Form::text('description', $paymentrequestretractapproval->description, ['class' => 'form-control', $attr]) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('created_at', '审批时间:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
        <div class='col-sm-10 col-xs-8'>
        {!! Form::datetimeLocal('created_at', $paymentrequestretractapproval->created_at, ['class' => 'form-control', $attr]) !!}
        </div>
    </div>
<hr>
    @endforeach
    <div class="reimb">
@endif
