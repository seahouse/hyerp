<div class="form-group">
    {!! Form::label('供应商名称', '供应商:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
    {!! Form::text('供应商名称', $purchaseorder->vendinfo->name, ['class' => 'form-control', 'readonly' => 'true']) !!}
    </div>
</div>


<div class="form-group">
    {!! Form::label('付款金额', '付款金额:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
    {!! Form::text('付款金额', $amount, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('付款日期', '付款日期:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
    {!! Form::date('付款日期', $paydate, ['class' => 'form-control']) !!}
    </div>
</div>


<div class="form-group">
    {!! Form::label('付款经办人ID', '付款人:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
    {!! Form::select('付款经办人ID', $payerList_hxold, null, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('付款说明', '付款说明:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
    {!! Form::textarea('付款说明', null, ['class' => 'form-control', 'rows' => 3]) !!}
    </div>
</div>


{!! Form::hidden('所属采购订单ID', $purchaseorder->id, ['class' => 'form-control']) !!}

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary']) !!}
    </div>
</div>
