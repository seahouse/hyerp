<div class="form-group">
    {!! Form::label('number', '订单编号:') !!}
    {!! Form::text('number', $salesorder->number, ['class' => 'form-control', 'readonly' => 'true']) !!}
</div>

<div class="form-group">
    {!! Form::label('', '客户:') !!}
    {!! Form::text('', $salesorder->custinfo->name, ['class' => 'form-control', 'readonly' => 'true']) !!}
</div>

<div class="form-group">
    {!! Form::label('amount', '金额:') !!}
    {!! Form::text('amount', $amount, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('recvdate', '收款日期:') !!}
    {!! Form::input('date', 'recvdate', $recvdate, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('notes', '备注:') !!}
    {!! Form::text('notes', null, ['class' => 'form-control']) !!}
</div>

{!! Form::hidden('sohead_id', $salesorder->id, ['class' => 'form-control']) !!}

<div class="form-group">
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary form-control']) !!}
</div>
