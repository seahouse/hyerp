<div class="reimb"><div class="form-d">

<div class="form-group">
    {!! Form::label('descrip', '付款事由:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::textarea('descrip', null, ['class' => 'form-control', 'placeholder' => '请输入付款事由（必填）', $attr, 'rows' => 3]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('supplier_name', '支付对象:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::text('supplier_name', $supplier_name, ['class' => 'form-control', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectSupplierModal', 'data-name' => 'supplier_name', 'data-id' => 'supplier_id']) !!}
    {!! Form::hidden('supplier_id', 0, ['class' => 'btn btn-sm', 'id' => 'supplier_id']) !!}
    @if (isset($reimbursement->customer_hxold->name)) 
        {!! Form::hidden('customer_name2', $reimbursement->customer_hxold->name, ['class' => 'btn btn-sm', 'id' => 'customer_name2']) !!}
    @else
        {!! Form::hidden('customer_name2', null, ['class' => 'btn btn-sm', 'id' => 'customer_name2']) !!}
    @endif
    </div>
</div>

<div class="form-group">
    {!! Form::label('pohead_number', '采购合同:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::text('pohead_number', $sohead_name, ['class' => 'form-control', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectOrderModal', 'data-name' => 'pohead_number', 'data-id' => 'pohead_id', 'data-supplierid' => 'supplier_id', 'data-poheadamount' => 'pohead_amount']) !!}
    {!! Form::hidden('pohead_id', 0, ['class' => 'btn btn-sm', 'id' => 'pohead_id']) !!}
    @if (isset($reimbursement->customer_hxold->name)) 
        {!! Form::hidden('customer_name2', $reimbursement->customer_hxold->name, ['class' => 'btn btn-sm', 'id' => 'customer_name2']) !!}
    @else
        {!! Form::hidden('customer_name2', null, ['class' => 'btn btn-sm', 'id' => 'customer_name2']) !!}
    @endif
    </div>
</div>

{{--
<div class="form-group">
    {!! Form::label('pohead_name', '采购合同:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::text('pohead_name', $pohead_name, ['class' => 'form-control', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectCustomerModal']) !!}
    {!! Form::hidden('customer_id', 0, ['class' => 'btn btn-sm', 'id' => 'customer_id']) !!}
    @if (isset($reimbursement->customer_hxold->name)) 
        {!! Form::hidden('customer_name2', $reimbursement->customer_hxold->name, ['class' => 'btn btn-sm', 'id' => 'customer_name2']) !!}
    @else
        {!! Form::hidden('customer_name2', null, ['class' => 'btn btn-sm', 'id' => 'customer_name2']) !!}
    @endif
    </div>
</div>
--}}

<div class="form-group">
    {!! Form::label('pohead_amount', '合同金额:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::text('pohead_amount', null, ['class' => 'form-control', 'readonly', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('pohead_amount_paid', '已付金额:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::text('pohead_amount_paid', null, ['class' => 'form-control', 'readonly', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('pohead_amount_ticketed', '已开票金额:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::text('pohead_amount_ticketed', null, ['class' => 'form-control', 'readonly', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('pohead_arrived', '到货情况:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::text('pohead_arrived', null, ['class' => 'form-control', 'readonly', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('sohead_process', '目前工程项目进度:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::text('sohead_process', null, ['class' => 'form-control', 'readonly', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('amount', '付款总额万元:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::text('amount', null, ['class' => 'form-control', 'placeholder' => '请输入付款总额（人民币）（必填）', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('paymentmethod', '付款方式:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::select('paymentmethod', array('现金' => '现金', '支票' => '支票', '转账' => '转账', '汇票' => '汇票'), null, ['class' => 'form-control', 'placeholder' => '付款方式', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('datepay', '付款日期:', ['for' => 'date', 'class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::date('datepay', $datepay, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('bank', '开户行:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::text('bank', null, ['class' => 'form-control', 'placeholder' => '请输入开户行（必填）', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('bankaccountnumber', '银行账号:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::text('bankaccountnumber', null, ['class' => 'form-control', 'placeholder' => '请输入银行账号（必填）', $attr]) !!}
    </div>
</div>



<hr>







{!! Form::hidden('applicant_id', null, ['class' => 'btn btn-sm']) !!}
{!! Form::hidden('approversetting_id', null, ['class' => 'btn btn-sm']) !!}

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
    {!! Form::submit($submitButtonText, ['class' => $btnclass, 'id' => 'btnSubmit']) !!}
    </div>
</div>
</div>
</div>




