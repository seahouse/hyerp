<div class="form-group">
    {!! Form::label('number', '编号:') !!}
    {!! Form::text('number', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('custinfo_id', '客户:') !!}
    {!! Form::select('custinfo_id', $custinfoList, null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('orderdate', '订单日期:') !!}
{{--    {!! Form::text('orderdate', null, ['class' => 'form-control datepicker']) !!} --}}
    {!! Form::input('date', 'orderdate', $orderdate, ['class' => 'form-control']) !!} 
</div>

<div class="form-group">
    {!! Form::label('shipto', '地址:') !!}
    {!! Form::text('shipto', null, ['class' => 'form-control']) !!}
</div>


<div class="form-group">
    {!! Form::label('salesrep_id', '销售代表:') !!}
    {!! Form::select('salesrep_id', $salesrepList, null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('comments', '备注:') !!}
    {!! Form::text('comments', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary form-control']) !!}
</div>
