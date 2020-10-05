<div class="form-group">
    {!! Form::label('number', '采购订单编号:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        @if (isset($pohead))
            {!! Form::text('number', $pohead->number, ['class' => 'form-control', 'readonly', $attr]) !!}
            {!! Form::hidden('pohead_id', $pohead->id) !!}
        @else
            {!! Form::text('number', null, ['class' => 'form-control', 'readonly', $attr]) !!}
        @endif
    </div>
</div>


<div class="form-group">
    {!! Form::label('supplier_id', '供应商:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::select('supplier_id', $suppliers, null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>


<div class="form-group">
    {!! Form::label('quote', '报价金额:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('quote', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>



<div class="form-group">
    {!! Form::label('remark', '备注:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('remark', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>



<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit($submitButtonText, ['class' => $btnclass, 'id' => 'btnSubmit']) !!}
    </div>
</div>
