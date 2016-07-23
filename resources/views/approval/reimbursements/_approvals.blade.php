@if (isset($reimbursement))
	<div class="reimb">
    @if ($reimbursement->reimbursementapprovals->count())
    <p class="bannerTitle">审批记录</p>
    @endif
    @foreach ($reimbursement->reimbursementapprovals as $reimbursementapproval)

    <div class="form-group">
        {!! Form::label('approver', '审批人:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
        <div class='col-sm-10 col-xs-8'>
        {!! Form::text('approver', $reimbursementapproval->approver->name, ['class' => 'form-control', $attr]) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('status', '审批结果:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
        <div class='col-sm-10 col-xs-8'>
        @if ($reimbursementapproval->status==0)
        {!! Form::text('status', '通过', ['class' => 'form-control', $attr]) !!}
        @else
        {!! Form::text('status', '未通过', ['class' => 'form-control', $attr]) !!}
        @endif
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('description', '审批描述:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
        <div class='col-sm-10 col-xs-8'>
        {!! Form::text('description', $reimbursementapproval->description, ['class' => 'form-control', $attr]) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('created_at', '审批时间:', ['class' => 'col-xs-6 col-sm-2 control-label']) !!}
        <div class='col-sm-10 col-xs-6'>
        {!! Form::date('created_at', $reimbursementapproval->created_at, ['class' => 'form-control', $attr]) !!}
        </div>
    </div>
<hr>
    @endforeach
    <div class="reimb">
@endif
