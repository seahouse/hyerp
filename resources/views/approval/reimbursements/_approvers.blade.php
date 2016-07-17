@if (isset($reimbursement))
	<div class="reimb" style="margin-top:-10px;">
    @if ($reimbursement->approversetting_id == 0)
    <div class="form-group">
        {!! Form::label('approver', '下一个审批人:', ['class' => 'col-xs-6 col-sm-2 control-label']) !!}
        <div class='col-xs-6 col-sm-10'>
        {!! Form::text('approver', '审批已结束', ['class' => 'form-control', $attr]) !!}
        </div>
    </div>
    @else
    <div class="form-group">
        {!! Form::label('approver', '下一个审批人:', ['class' => 'col-xs-6 col-sm-2 control-label']) !!}
        <div class='col-xs-6 col-sm-10'>
        @if ($reimbursement->nextapprover())
        {!! Form::text('approver', $reimbursement->nextapprover()->name, ['class' => 'form-control', $attr]) !!}
        @else
        {!! Form::text('approver', '--', ['class' => 'form-control', $attr]) !!}
        @endif
        </div>
    </div>
    @endif
    </div>
@endif
