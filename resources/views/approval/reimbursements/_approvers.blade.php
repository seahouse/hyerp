@if (isset($reimbursement))
    @if ($reimbursement->approversetting_id == 0)
    <div class="form-group">
        {!! Form::label('approver', '下一个审批人:', ['class' => 'col-sm-2 control-label']) !!}
        <div class='col-sm-10'>
        {!! Form::text('approver', '审批已结束', ['class' => 'form-control', $attr]) !!}
        </div>
    </div>
    @else
    <div class="form-group">
        {!! Form::label('approver', '下一个审批人:', ['class' => 'col-sm-2 control-label']) !!}
        <div class='col-sm-10'>
        @if ($reimbursement->nextapprover())
        {!! Form::text('approver', $reimbursement->nextapprover()->name, ['class' => 'form-control', $attr]) !!}
        @else
        {!! Form::text('approver', '--', ['class' => 'form-control', $attr]) !!}
        @endif
        </div>
    </div>
    @endif
@endif
