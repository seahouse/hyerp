<div class="form-group">
    {!! Form::label('', '用户名称:') !!}
    {!! Form::text('', $purchaseorder->number, ['class' => 'form-control', 'readonly' => 'true']) !!}
</div>

<div class="form-group">
    {!! Form::label('', '姓名:') !!}
    {!! Form::text('', $purchaseorder->vendinfo->name, ['class' => 'form-control', 'readonly' => 'true']) !!}
</div>

<div class="form-group">
    {!! Form::label('amount', '金额:') !!}
    {!! Form::text('amount', $amount, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('paydate', '收款日期:') !!}
    {!! Form::input('date', 'paydate', $paydate, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('notes', '备注:') !!}
    {!! Form::text('notes', null, ['class' => 'form-control']) !!}
</div>

{!! Form::hidden('pohead_id', $purchaseorder->id, ['class' => 'form-control']) !!}

<div class="form-group">
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary form-control']) !!}
</div>
