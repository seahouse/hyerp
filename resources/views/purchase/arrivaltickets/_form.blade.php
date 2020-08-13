<div class="form-group">
    {!! Form::label('发票号码', '发票号码:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
    {!! Form::text('发票号码', null, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('到票金额', '到票金额:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
    {!! Form::text('到票金额', null, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('到票日期', '到票日期:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
    {!! Form::date('到票日期', null, ['class' => 'form-control']) !!}
    </div>
</div>


<div class="form-group">
    {!! Form::label('收票人', '经办人:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
    {!! Form::select('收票人', $userList_hxold, null, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('到票说明', '备注:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
    {!! Form::textarea('到票说明', null, ['class' => 'form-control', 'rows' => 3]) !!}
    </div>
</div>


{!! Form::hidden('所属采购订单ID', $purchaseorder->id, ['class' => 'form-control']) !!}

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
    {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary']) !!}
    </div>
</div>
