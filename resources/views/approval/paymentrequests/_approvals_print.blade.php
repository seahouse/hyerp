@if (isset($paymentrequest))
	<div class="reimb">
    @if ($paymentrequest->paymentrequestapprovals->count())
    <p class="bannerTitle">审批记录</p>
    @endif
    @foreach ($paymentrequest->paymentrequestapprovals as $paymentrequestapproval)

    <div class="form-group">
        {!! Form::label('approver', '审批人:', ['class' => 'col-xs-2 col-sm-2 control-label']) !!}
        <div class='col-sm-4 col-xs-4'>
        {!! Form::text('approver', $paymentrequestapproval->approver->name, ['class' => 'form-control', $attr]) !!}
        </div>

        {!! Form::label('status', '审批结果:', ['class' => 'col-xs-2 col-sm-2 control-label']) !!}
        <div class='col-sm-4 col-xs-4'>
        @if ($paymentrequestapproval->status==0)
        {!! Form::text('status', '通过', ['class' => 'form-control', $attr]) !!}
        @else
        {!! Form::text('status', '未通过', ['class' => 'form-control', $attr]) !!}
        @endif
        </div>
    </div>

{{--
    <div class="form-group">
        {!! Form::label('status', '审批结果:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
        <div class='col-sm-10 col-xs-8'>
        @if ($paymentrequestapproval->status==0)
        {!! Form::text('status', '通过', ['class' => 'form-control', $attr]) !!}
        @else
        {!! Form::text('status', '未通过', ['class' => 'form-control', $attr]) !!}
        @endif
        </div>
    </div>
--}}

    <div class="form-group">
        {!! Form::label('description', '审批描述:', ['class' => 'col-xs-2 col-sm-2 control-label']) !!}
        <div class='col-sm-4 col-xs-4'>
        {!! Form::text('description', $paymentrequestapproval->description, ['class' => 'form-control', $attr]) !!}
        </div>

        {!! Form::label('created_at', '审批时间:', ['class' => 'col-xs-2 col-sm-2 control-label']) !!}
        <div class='col-sm-4 col-xs-4'>
        {!! Form::datetimeLocal('created_at', $paymentrequestapproval->created_at, ['class' => 'form-control', $attr]) !!}
        </div>
    </div>

{{--
    <div class="form-group">
        {!! Form::label('created_at', '审批时间:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
        <div class='col-sm-10 col-xs-8'>
        {!! Form::datetimeLocal('created_at', $paymentrequestapproval->created_at, ['class' => 'form-control', $attr]) !!}
        </div>
    </div>
--}}
<hr>
    @endforeach
    <div class="reimb">
@endif
