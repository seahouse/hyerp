<div class="form-group">
    {!! Form::label('company_name', '采购公司:', ['class' => 'col-xs-2 col-sm-2 control-label ','style'=>'font-size:20px;font-weight:bold ']) !!}
    <div class='col-xs-8 col-sm-10'>
        @if (isset($paymentrequest->purchaseorder_hxold->companyname))
            {!! Form::text('company_name', $paymentrequest->purchaseorder_hxold->companyname, ['class' => 'form-control ','style'=>'font-size:20px;font-weight:bold ', $attr,]) !!}
        @else
            {!! Form::text('company_name', null, ['class' => 'form-control ','style'=>'font-size:20px font-weight:bold', $attr]) !!}
        @endif
    </div>
</div>

<div class="form-group">
    {!! Form::label('suppliertype', '供应商类型:', ['class' => 'col-xs-2 col-sm-2 control-label']) !!}
    <div class='col-xs-4 col-sm-4'>
    {!! Form::select('suppliertype', array('安装公司' => '安装公司', '机务设备类' => '机务设备类', '电气设备类' => '电气设备类', '安装材料类' => '安装材料类', '代理或服务类' => '代理或服务类', '厂部常用类' => '厂部常用类', '其他' => '其他'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable]) !!}
    </div>

    {!! Form::label('paymenttype', '付款类型:', ['class' => 'col-xs-2 col-sm-2 control-label']) !!}
    <div class='col-xs-4 col-sm-4'>
    {!! Form::select('paymenttype', array('预付款' => '预付款', '进度款' => '进度款', '到货款' => '到货款', '安装结束款' => '安装结束款', '调试运行款' => '调试运行款', '环保验收款' => '环保验收款', '质保金' => '质保金'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable]) !!}
    </div>
</div>



@if (isset($paymentrequest))
<div class="form-group">
    {!! Form::label('supplier_name', '支付对象:', ['class' => 'col-xs-2 col-sm-2 control-label']) !!}
    <div class='col-xs-10 col-sm-10'>
    @if (isset($paymentrequest->supplier_hxold->name))

        {!! Form::text('supplier_name', $paymentrequest->supplier_hxold->name, ['class' => 'form-control', $attr]) !!}
    @else
        {!! Form::text('supplier_name', null, ['class' => 'form-control', $attr]) !!}
    @endif
    </div>
</div>

<div class="form-group">
    {!! Form::label('pohead_number', '采购合同:', ['class' => 'col-xs-2 col-sm-2 control-label']) !!}
    <div class='col-xs-4 col-sm-4'>
    @if (isset($paymentrequest->purchaseorder_hxold->number))
         {!! Form::text('pohead_number', $paymentrequest->purchaseorder_hxold->number, ['class' => 'form-control', $attr]) !!}
    @else
        {!! Form::text('pohead_number', null, ['class' => 'form-control', $attr]) !!}
    @endif
    </div>

</div>

{{--<div class="form-group">--}}
    {{--{!! Form::label('pohead_descrip', '对应工程名称:', ['class' => 'col-xs-2 col-sm-2 control-label']) !!}--}}
    {{--<div class='col-xs-10 col-sm-10'>--}}
    {{--@if (isset($paymentrequest->purchaseorder_hxold->sohead->custinfo->name)) --}}
         {{--{!! Form::textarea('pohead_descrip', $paymentrequest->purchaseorder_hxold->sohead->custinfo->name . ' | ' . $paymentrequest->purchaseorder_hxold->sohead->descrip, ['class' => 'form-control', $attr, 'rows' => 2]) !!}--}}
    {{--@else--}}
        {{--{!! Form::textarea('pohead_descrip', null, ['class' => 'form-control', $attr, 'rows' => 2]) !!}--}}
    {{--@endif--}}
    {{--</div>--}}
{{--</div>--}}

<div class="form-group">
    {!! Form::label('pohead_amount', '合同金额:', ['class' => 'col-xs-2 col-sm-2 control-label']) !!}
    <div class='col-xs-4 col-sm-4'>
    @if (isset($paymentrequest->purchaseorder_hxold->amount)) 
         {!! Form::text('pohead_amount', $paymentrequest->purchaseorder_hxold->amount, ['class' => 'form-control', $attr]) !!}
    @else
        {!! Form::text('pohead_amount', null, ['class' => 'form-control', $attr]) !!}
    @endif
    </div>

    {!! Form::label('pohead_amount_paid', '已付金额:', ['class' => 'col-xs-2 col-sm-2 control-label']) !!}
    <div class='col-xs-4 col-sm-4'>
    @if (isset($paymentrequest->purchaseorder_hxold->amount_paid)) 
         {!! Form::text('pohead_amount', $paymentrequest->purchaseorder_hxold->amount_paid, ['class' => 'form-control', $attr]) !!}
    @else
        {!! Form::text('pohead_amount', null, ['class' => 'form-control', $attr]) !!}
    @endif
    </div>
{{--
    @if (isset($paymentrequest->purchaseorder_hxold->amount_paid) and isset($paymentrequest->purchaseorder_hxold->amount) and $paymentrequest->purchaseorder_hxold->amount > 0.0)
        {!! Form::label('amount_paid_percent', number_format($paymentrequest->purchaseorder_hxold->amount_paid / $paymentrequest->purchaseorder_hxold->amount * 100.0, 2, '.', '') . '%', ['class' => 'col-xs-2 col-sm-2 control-label']) !!}
    @else
        {!! Form::label('amount_paid_percent', '-', ['class' => 'col-xs-2 col-sm-2 control-label', 'id' => 'amount_paid_percent']) !!}
    @endif
--}}
</div>


<div class="form-group">
    {!! Form::label('pohead_amount_ticketed', '已开票金额:', ['class' => 'col-xs-2 col-sm-2 control-label']) !!}
    <div class='col-xs-4 col-sm-4'>
    @if (isset($paymentrequest->purchaseorder_hxold->amount_ticketed)) 
         {!! Form::text('pohead_amount_ticketed', $paymentrequest->purchaseorder_hxold->amount_ticketed, ['class' => 'form-control', $attr]) !!}
    @else
        {!! Form::text('pohead_amount_ticketed', null, ['class' => 'form-control', $attr]) !!}
    @endif
    </div>
{{--
    @if (isset($paymentrequest->purchaseorder_hxold->amount_ticketed) and isset($paymentrequest->purchaseorder_hxold->amount) and $paymentrequest->purchaseorder_hxold->amount > 0.0)
        {!! Form::label('amount_ticketed_percent', number_format($paymentrequest->purchaseorder_hxold->amount_ticketed / $paymentrequest->purchaseorder_hxold->amount * 100.0, 2, '.', '') . '%', ['class' => 'col-xs-3 col-sm-1 control-label']) !!}
    @else
        {!! Form::label('amount_ticketed_percent', '-', ['class' => 'col-xs-3 col-sm-1 control-label', 'id' => 'amount_ticketed_percent']) !!}
    @endif
--}}

    {!! Form::label('pohead_arrived', '到货情况:', ['class' => 'col-xs-2 col-sm-2 control-label']) !!}
    <div class='col-xs-4 col-sm-4'>
    @if (isset($paymentrequest->purchaseorder_hxold->arrival_percent))
        @if ($paymentrequest->purchaseorder_hxold->arrival_percent <= 0.0)
            {!! Form::text('pohead_arrived', '未到货', ['class' => 'form-control', $attr]) !!}
        @elseif ($paymentrequest->purchaseorder_hxold->arrival_percent > 0.0 and $paymentrequest->purchaseorder_hxold->arrival_percent < 0.99)
            {!! Form::text('pohead_arrived', '部分到货', ['class' => 'form-control', $attr]) !!}
        @else
            {!! Form::text('pohead_arrived', '全部到货', ['class' => 'form-control', $attr]) !!}
        @endif
    @else
        {!! Form::text('pohead_arrived', null, ['class' => 'form-control', $attr]) !!}
    @endif
    </div>
</div>

{{--
<div class="form-group">
    {!! Form::label('pohead_arrived', '到货情况:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    @if (isset($paymentrequest->purchaseorder_hxold->arrival_percent))
        @if ($paymentrequest->purchaseorder_hxold->arrival_percent <= 0.0)
            {!! Form::text('pohead_arrived', '未到货', ['class' => 'form-control', $attr]) !!}
        @elseif ($paymentrequest->purchaseorder_hxold->arrival_percent > 0.0 and $paymentrequest->purchaseorder_hxold->arrival_percent < 0.99)
            {!! Form::text('pohead_arrived', '部分到货', ['class' => 'form-control', $attr]) !!}
        @else
            {!! Form::text('pohead_arrived', '全部到货', ['class' => 'form-control', $attr]) !!}
        @endif
    @else
        {!! Form::text('pohead_arrived', null, ['class' => 'form-control', $attr]) !!}
    @endif
    </div>
</div>
--}}

<div class="form-group">
    {!! Form::label('paymethod', '付款方式:', ['class' => 'col-xs-2 col-sm-2 control-label' ]) !!}
    <div class='col-xs-10 col-sm-10'>
    @if (isset($paymentrequest->purchaseorder_hxold->paymethod)) 
        {!! Form::textarea('paymethod', $paymentrequest->purchaseorder_hxold->paymethod, ['class' => 'form-control', $attr, 'rows' => 2]) !!}
    @else
        {!! Form::textarea('paymethod', null, ['class' => 'form-control', $attr, 'rows' => 2]) !!}
    @endif
    </div>
</div>



<div class="form-group">
    {!! Form::label('sohead_installeddate', '安装完毕日期:', ['class' => 'col-xs-2 col-sm-2 control-label' ]) !!}
    <div class='col-xs-10 col-sm-10'>
    @if (isset($paymentrequest->purchaseorder_hxold->sohead->installeddate)) 
        {!! Form::text('sohead_installeddate', substr($paymentrequest->purchaseorder_hxold->sohead->installeddate, 0, 10), ['class' => 'form-control', $attr]) !!}
    @else
        {!! Form::text('sohead_installeddate', null, ['class' => 'form-control', $attr]) !!}
    @endif
    </div>
</div>

<div class="form-group">
    {!! Form::label('pohead_productname', '采购商品名称:', ['class' => 'col-xs-2 col-sm-2 control-label' ]) !!}
    <div class='col-xs-10 col-sm-10'>
    @if (isset($paymentrequest->purchaseorder_hxold->productname)) 
        {!! Form::text('pohead_productname', $paymentrequest->purchaseorder_hxold->productname, ['class' => 'form-control', $attr]) !!}
    @else
        {!! Form::text('pohead_productname', null, ['class' => 'form-control', $attr]) !!}
    @endif
    </div>
</div>



@else
<div class="form-group">
    {!! Form::label('supplier_name', '支付对象:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::text('supplier_name', $supplier_name, ['class' => 'form-control', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectSupplierModal', 'data-name' => 'supplier_name', 'data-id' => 'supplier_id']) !!}
    {!! Form::hidden('supplier_id', 0, ['class' => 'btn btn-sm', 'id' => 'supplier_id']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('pohead_number', '采购合同:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::text('pohead_number', $pohead_number, ['class' => 'form-control', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectOrderModal', 'data-name' => 'pohead_number', 'data-id' => 'pohead_id', 'data-supplierid' => 'supplier_id', 'data-poheadamount' => 'pohead_amount']) !!}
    {!! Form::hidden('pohead_id', 0, ['class' => 'btn btn-sm', 'id' => 'pohead_id']) !!}
    @if (isset($reimbursement->customer_hxold->name)) 
        {!! Form::hidden('customer_name2', $reimbursement->customer_hxold->name, ['class' => 'btn btn-sm', 'id' => 'customer_name2']) !!}
    @else
        {!! Form::hidden('customer_name2', null, ['class' => 'btn btn-sm', 'id' => 'customer_name2']) !!}
    @endif
    </div>
</div>

<div class="form-group">
    {!! Form::label('company_name', '采购公司:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('company_name', null, ['class' => 'form-control', 'readonly', $attr]) !!}
    </div>
</div>


<div class="form-group">
    {!! Form::label('pohead_descrip', '对应工程名称:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::textarea('pohead_descrip', null, ['class' => 'form-control', 'readonly', $attr, 'rows' => 3]) !!}
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
    <div class='col-xs-5 col-sm-9'>
    {!! Form::text('pohead_amount_paid', null, ['class' => 'form-control', 'readonly', $attr]) !!}
    </div>
    {!! Form::label('amount_paid_percent', '-', ['class' => 'col-xs-3 col-sm-1 control-label', 'id' => 'amount_paid_percent']) !!}
</div>

<div class="form-group">
    {!! Form::label('pohead_amount_ticketed', '已开票金额:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-5 col-sm-9'>
    {!! Form::text('pohead_amount_ticketed', null, ['class' => 'form-control', 'readonly', $attr]) !!}
    </div>
    {!! Form::label('amount_ticketed_percent', '-', ['class' => 'col-xs-3 col-sm-1 control-label', 'id' => 'amount_ticketed_percent']) !!}
</div>

<div class="form-group">
    {!! Form::label('pohead_arrived', '到货情况:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::text('pohead_arrived', null, ['class' => 'form-control', 'readonly', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('paymethod', '付款方式:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::textarea('paymethod', null, ['class' => 'form-control', 'readonly', $attr, 'rows' => 3]) !!}
    </div>
</div>

{{--
<div class="form-group">
    {!! Form::label('paymethod', '订单付款方式:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::textarea('paymethod', null, ['class' => 'form-control', 'readonly', $attr, 'rows' => 3]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('sohead_paymethod_descrip', '订单付款备注:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::textarea('sohead_paymethod_descrip', null, ['class' => 'form-control', 'readonly', $attr, 'rows' => 3]) !!}
    </div>
</div>
--}}

<div class="form-group">
    {!! Form::label('sohead_installeddate', '安装完毕日期:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::text('sohead_installeddate', null, ['class' => 'form-control', 'readonly', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('pohead_productname', '采购商品名称:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::text('pohead_productname', null, ['class' => 'form-control', 'readonly', $attr]) !!}
    </div>
</div>

@endif

{{--
<div class="form-group">
    {!! Form::label('equipmentname', '应付款设备名称:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::text('equipmentname', null, ['class' => 'form-control', 'placeholder' => '请输入本次应付款的大体设备名称', $attr]) !!}
    </div>
</div>
--}}

@if (isset($paymentrequest->supplier_hxold->name))
    @if (in_array($paymentrequest->supplier_hxold->name, config('custom.paymentrequest.printpage_descrip_show_suppliers')))
        <div class="form-group">
            {!! Form::label('descrip', '说明:', ['class' => 'col-xs-2 col-sm-2 control-label' ]) !!}
            <div class='col-xs-10 col-sm-10'>
            {!! Form::textarea('descrip', null, ['class' => 'form-control', $attr, 'rows' => 1]) !!}
            </div>
        </div>
    @endif
@endif

<div class="form-group">
    {!! Form::label('amount', '本次请款额:', ['class' => 'col-xs-2 col-sm-2 control-label']) !!}
    <div class='col-xs-4 col-sm-4'>
    {!! Form::text('amount', null, ['class' => 'form-control', 'placeholder' => '请输入付款总额（人民币）（必填）', $attr]) !!}
    </div>
{{--
    @if (isset($paymentrequest) and isset($paymentrequest->purchaseorder_hxold->amount) and $paymentrequest->purchaseorder_hxold->amount > 0.0)
        {!! Form::label('amount_percent', number_format($paymentrequest->amount / $paymentrequest->purchaseorder_hxold->amount * 100.0, 2, '.', '') . '%', ['class' => 'col-xs-3 col-sm-1 control-label']) !!}
    @else
        {!! Form::label('amount_percent', '-', ['class' => 'col-xs-3 col-sm-1 control-label', 'id' => 'amount_percent']) !!}
    @endif
--}}

        {!! Form::label('datepay', '付款日期:', ['for' => 'date', 'class' => 'col-xs-2 col-sm-2 control-label']) !!}
        <div class='col-xs-4 col-sm-4'>
            {!! Form::date('datepay', $datepay, ['class' => 'form-control', $attr]) !!}
        </div>
</div>

{{--
<div class="form-group">
    {!! Form::label('paymentmethod', '付款方式:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::select('paymentmethod', array('支票' => '支票', '贷记' => '贷记', '电汇' => '电汇', '汇票' => '汇票', '现金' => '现金', '银行卡' => '银行卡', '其他' => '其他'), null, ['class' => 'form-control', 'placeholder' => '付款方式', $attr, $attrdisable]) !!}
    </div>
</div>
--}}




@if (isset($paymentrequest))
<div class="form-group">
    {!! Form::label('paymentmethod', '付款方式:', ['class' => 'col-xs-2 col-sm-2 control-label']) !!}
    <div class='col-xs-4 col-sm-4'>
        {!! Form::select('paymentmethod', array('支票' => '支票', '贷记' => '贷记', '电汇' => '电汇', '汇票' => '汇票', '现金' => '现金', '银行卡' => '银行卡', '其他' => '其他'), null, ['class' => 'form-control', 'placeholder' => '付款方式', $attr, $attrdisable]) !!}
    </div>

    {!! Form::label('supplier_bank', '开户行:', ['class' => 'col-xs-2 col-sm-2 control-label']) !!}
    <div class='col-xs-4 col-sm-4'>
    @if (isset($paymentrequest->vendbank_hxold->bankname)) 
        {!! Form::text('supplier_bank', $paymentrequest->vendbank_hxold->bankname, ['class' => 'form-control', $attr]) !!}
    @else
        {!! Form::text('supplier_bank', null, ['class' => 'form-control', $attr]) !!}
    @endif
    </div>
</div>

<div class="form-group">
    {!! Form::label('supplier_bankaccountnumber', '银行账号:', ['class' => 'col-xs-2 col-sm-2 control-label']) !!}
    <div class='col-xs-4 col-sm-4'>
    @if (isset($paymentrequest->vendbank_hxold->accountnum)) 
        {!! Form::text('supplier_bankaccountnumber', $paymentrequest->vendbank_hxold->accountnum, ['class' => 'form-control', $attr]) !!}
    @else
        {!! Form::text('supplier_bankaccountnumber', null, ['class' => 'form-control', $attr]) !!}
    @endif
    </div>

    {!! Form::label('paymentmethod2', '付款方式2:', ['class' => 'col-xs-2 col-sm-2 control-label']) !!}
    <div class='col-xs-4 col-sm-4'>
        {!! Form::select('paymentmethod2', array('支票' => '支票', '贷记' => '贷记', '电汇' => '电汇', '汇票' => '汇票', '现金' => '现金', '银行卡' => '银行卡', '其他' => '其他'), null, ['class' => 'form-control', 'placeholder' => '付款方式', $attr, $attrdisable]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('supplier_bank2', '开户行2:', ['class' => 'col-xs-2 col-sm-2 control-label']) !!}
    <div class='col-xs-4 col-sm-4'>
        @if (isset($paymentrequest->vendbank_hxold2->bankname))
            {!! Form::text('supplier_bank2', $paymentrequest->vendbank_hxold2->bankname, ['class' => 'form-control', $attr]) !!}
        @else
            {!! Form::text('supplier_bank2', null, ['class' => 'form-control', $attr]) !!}
        @endif
    </div>

    {!! Form::label('supplier_bankaccountnumber2', '银行账号:', ['class' => 'col-xs-2 col-sm-2 control-label']) !!}
    <div class='col-xs-4 col-sm-4'>
        @if (isset($paymentrequest->vendbank_hxold2->accountnum))
            {!! Form::text('supplier_bankaccountnumber2', $paymentrequest->vendbank_hxold2->accountnum, ['class' => 'form-control', $attr]) !!}
        @else
            {!! Form::text('supplier_bankaccountnumber2', null, ['class' => 'form-control', $attr]) !!}
        @endif
    </div>
</div>

<div class="form-group">
    {!! Form::label('created_at', '发起时间:', ['class' => 'col-xs-2 col-sm-2 control-label']) !!}
    <div class='col-xs-4 col-sm-4'>
        {!! Form::text('created_at', $paymentrequest->created_at, ['class' => 'form-control', $attr]) !!}
    </div>

    {!! Form::label('applicant', '申请人:', ['class' => 'col-xs-2 col-sm-2 control-label']) !!}
    <div class='col-xs-4 col-sm-4'>
        {!! Form::text('applicant', $paymentrequest->applicant->name, ['class' => 'form-control', $attr]) !!}
    </div>
    {{--
    @if ($paymentrequest->approversetting_id == 0)
        @if ($paymentrequest->paymentrequestapprovals->count())
            {!! Form::label('last_approval_created_at', '审批时间:', ['class' => 'col-xs-2 col-sm-2 control-label']) !!}
            <div class='col-xs-4 col-sm-4'>
                {!! Form::text('last_approval_created_at', $paymentrequest->paymentrequestapprovals->last()->created_at, ['class' => 'form-control', $attr]) !!}
            </div>
        @endif
    @endif
    --}}
</div>

@if (isset($paymentrequest) && strlen($paymentrequest->associated_approval_type) > 0)
    <div class="form-group">
        {!! Form::label('associated_approval_type', '关联审批单类型:', ['class' => 'col-xs-2 col-sm-2 control-label']) !!}
        <div class='col-xs-4 col-sm-4'>
            {!! Form::select('associated_approval_type', array('corporatepayment' => '付款-对公帐户付款'), $paymentrequest->associated_approval_type, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable]) !!}
        </div>

        {!! Form::label('associated_remark', '关联审批单备注:', ['class' => 'col-xs-2 col-sm-2 control-label']) !!}
        <div class='col-xs-4 col-sm-4'>
            {!! Form::text('associated_remark', $paymentrequest->associated_remark, ['class' => 'form-control', $attr]) !!}
        </div>
    </div>
@endif

@else
    记录异常，请联系管理员。
@endif


<div class="form-group">
    {!! Form::label('paymentnodeattachments', '付款节点审批单:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
{{--
    <将在钉钉的下一个版本中支持上传附件>
--}}

    
{{--
    {!! Form::button('+', ['class' => 'btn btn-sm', 'id' => 'btnSelectPaymentnodeattachment']) !!}
--}}
    <div class='col-xs-8 col-sm-10'>
        @if (isset($paymentrequest))
            @foreach ($paymentrequest->paymentnodes() as $paymentnode)
                 <a href="{!! $paymentnode->path !!}" target="_blank">{{ $paymentnode->filename }}</a> <br>
            @endforeach
        @else
            {!! Form::file('paymentnodeattachments[]', ['multiple']) !!}
        @endif
    </div>
</div>

{{--<div class="form-group">--}}
    {{--{!! Form::label('businesscontractattachments', '商务合同等必要附件:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}--}}
    {{--<div class='col-xs-8 col-sm-10'>--}}
        {{--@if (isset($paymentrequest))--}}

            {{--@if (isset($paymentrequest->purchaseorder_hxold->businesscontract))--}}
                {{--<a href="{!! config('custom.hxold.purchase_businesscontract_webdir') . $paymentrequest->purchaseorder_hxold->id . '/' . $paymentrequest->purchaseorder_hxold->businesscontract !!}" target="_blank">{{ $paymentrequest->purchaseorder_hxold->businesscontract }}</a> <br>--}}
            {{--@endif--}}

            {{--@foreach ($paymentrequest->businesscontracts() as $businesscontract)--}}
                {{--<a href="{!! $businesscontract->path !!}" target="_blank">{{ $businesscontract->filename }}</a> <br>--}}
            {{--@endforeach--}}
        {{--@else--}}
            {{--{!! Form::file('businesscontractattachments[]', ['multiple']) !!}--}}
        {{--@endif--}}
    {{--</div>--}}
{{--</div>--}}

{{--<div class="form-group">--}}
    {{--{!! Form::label('images', '图片说明:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}--}}
    {{----}}
    {{--<div class='col-xs-8 col-sm-10'>--}}
        {{--@if (isset($paymentrequest))--}}
            {{--<div class="row" id="previewimage">--}}
                {{--@foreach ($paymentrequest->paymentrequestimages() as $paymentrequestimage)--}}
                    {{--<div class="col-xs-6 col-md-3">--}}
                        {{--<div class="thumbnail">--}}
                            {{--<img src="{!! $paymentrequestimage->path !!}" />--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--@endforeach--}}
            {{--</div>--}}
        {{--@else--}}
            {{--@if ($agent->isDesktop())--}}
                {{--{!! Form::file('images[]', ['multiple']) !!}--}}
            {{--@else--}}
                {{--{!! Form::button('+', ['class' => 'btn btn-sm', 'id' => 'btnSelectImage']) !!}--}}
            {{--@endif            --}}
        {{--@endif--}}

    {{--</div>--}}
{{--</div>--}}






{!! Form::hidden('applicant_id', null, ['class' => 'btn btn-sm']) !!}
{!! Form::hidden('approversetting_id', null, ['class' => 'btn btn-sm']) !!}

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
    {!! Form::submit($submitButtonText, ['class' => $btnclass, 'id' => 'btnSubmit']) !!}
    </div>
</div>



