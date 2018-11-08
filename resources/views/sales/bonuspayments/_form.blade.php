<div class="reimb"><div class="form-d">

<div class="form-group">
    {!! Form::label('sohead_name', '对应订单:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('sohead_name', $sohead->projectjc, ['class' => 'form-control', 'readonly']) !!}
        {!! Form::hidden('sohead_id', $sohead->id) !!}
    </div>
</div>

        <div class="form-group">
            {!! Form::label('paymentdate', '支付日期:', ['for' => 'date', 'class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::date('paymentdate', $datepay, ['class' => 'form-control', $attr]) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('bonusfactorpercent', '奖金系数:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text('bonusfactorpercent', $sohead->getBonusfactorByPolicy() * 100.0 . '%', ['class' => 'form-control', 'readonly']) !!}
                {!! Form::hidden('bonusfactor', $sohead->getBonusfactorByPolicy()) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('amountpertenthousandbysohead', '奖金比例:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text('amountpertenthousandbysohead', array_first($sohead->getAmountpertenthousandBySohead())->amountpertenthousandbysohead, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('amount', '支付金额:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text('amount', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('remark', '备注:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text('remark', null, ['class' => 'form-control']) !!}
            </div>
        </div>

@if (isset($paymentrequest))






            <div class="form-group">
                {!! Form::label('pohead_taxrate_str', '税率信息:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                <div class='col-xs-8 col-sm-10'>
                    @if (isset($paymentrequest->purchaseorder_hxold->poheadtaxrate_str))
                        {!! Form::text('pohead_taxrate_str', $paymentrequest->purchaseorder_hxold->poheadtaxrate_str, ['class' => 'form-control', $attr]) !!}
                    @else
                        {!! Form::text('pohead_taxrate_str', null, ['class' => 'form-control', $attr]) !!}
                    @endif
                </div>
            </div>

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

<div class="form-group">
    {!! Form::label('paymethod', '付款方式:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}
    <div class='col-xs-8 col-sm-10'>
    @if (isset($paymentrequest->purchaseorder_hxold->paymethod)) 
        {!! Form::textarea('paymethod', $paymentrequest->purchaseorder_hxold->paymethod, ['class' => 'form-control', $attr, 'rows' => 3]) !!}
    @else
        {!! Form::textarea('paymethod', null, ['class' => 'form-control', $attr, 'rows' => 3]) !!}
    @endif
    </div>
</div>


<div class="form-group">
    {!! Form::label('sohead_installeddate', '安装完毕日期:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}
    <div class='col-xs-8 col-sm-10'>
    @if (isset($paymentrequest->purchaseorder_hxold->sohead->installeddate)) 
        {!! Form::text('sohead_installeddate', substr($paymentrequest->purchaseorder_hxold->sohead->installeddate, 0, 10), ['class' => 'form-control', $attr]) !!}
    @else
        {!! Form::text('sohead_installeddate', null, ['class' => 'form-control', $attr]) !!}
    @endif
    </div>
</div>

<div class="form-group">
    {!! Form::label('pohead_productname', '采购商品名称:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}
    <div class='col-xs-8 col-sm-10'>
    @if (isset($paymentrequest->purchaseorder_hxold->productname)) 
        {!! Form::text('pohead_productname', $paymentrequest->purchaseorder_hxold->productname, ['class' => 'form-control', $attr]) !!}
    @else
        {!! Form::text('pohead_productname', null, ['class' => 'form-control', $attr]) !!}
    @endif
    </div>
</div>

@can('approval_paymentrequest_recvdetail_view')

<div class="form-group">
    <div class='col-xs-4 col-sm-2'>
    </div>
    <div class='col-xs-8 col-sm-10'>
@if (Auth::user()->email == "admin@admin.com")
    @if (isset($paymentrequest->purchaseorder_hxold->id))
        <a href="{{ URL::to('/purchase/purchaseorders/' . $paymentrequest->purchaseorder_hxold->id . '/detail_hxold') }}" target="_blank">入库价格明细</a>
    @endif
@endif
    <a href="{{ URL::to('/approval/paymentrequests/' . $paymentrequest->id . '/mrecvdetail') }}" target="_blank" class="btn btn-default btn-sm" id="t1">入库价格明细</a>
    <a href="{{ URL::to('/approval/paymentrequests/' . $paymentrequest->id . '/mrecvdetail2') }}" target="_blank" class="btn btn-default btn-sm">入库价格明细2</a>
    <a href="{{ URL::to('/approval/paymentrequests/' . $paymentrequest->id . '/mrecvdetail3') }}" target="_blank" class="btn btn-default btn-sm">入库价格明细3</a>
    <a href="{{ URL::to('/approval/paymentrequests/' . $paymentrequest->id . '/mrecvdetail4') . "?dd_orientation=landscape" }}" target="_blank" class="btn btn-default btn-sm">入库明细</a>
    <a href="{{ URL::to('/approval/paymentrequests/' . $paymentrequest->id . '/mrecvdetail5') . "?dd_orientation=landscape" }}" target="_blank" class="btn btn-default btn-sm">入库明细(新版)</a>
    </div>
</div>

<div class="form-group">
    <div class='col-xs-4 col-sm-2'>
    </div>
    <div class='col-xs-8 col-sm-10'>
        @if (isset($paymentrequest->purchaseorder_hxold->sohead->id))
            <a href="{{ URL::to('/sales/salesorders/' . $paymentrequest->purchaseorder_hxold->sohead->id . '/mstatistics') }}" target="_blank" class="btn btn-default btn-sm">对应的销售订单金额数据统计</a>
        @endif
    </div>
</div>
@endcan

@else
    {{--
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
    {!! Form::label('pohead_descrip', '对应工程名称:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::textarea('pohead_descrip', null, ['class' => 'form-control', 'readonly', $attr, 'rows' => 3]) !!}
    </div>
</div>

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
--}}
@endif

{{--
<div class="form-group">
    {!! Form::label('descrip', '说明:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::textarea('descrip', null, ['class' => 'form-control', $attr, 'rows' => 3]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('amount', '本次请款额:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-5 col-sm-9'>
    {!! Form::text('amount', null, ['class' => 'form-control', 'placeholder' => '请输入付款总额（人民币）（必填）', 'id' => 'amount', $attr]) !!}
    </div>
    @if (isset($paymentrequest) and isset($paymentrequest->purchaseorder_hxold->amount) and $paymentrequest->purchaseorder_hxold->amount > 0.0)
        {!! Form::label('amount_percent', number_format($paymentrequest->amount / $paymentrequest->purchaseorder_hxold->amount * 100.0, 2, '.', '') . '%', ['class' => 'col-xs-3 col-sm-1 control-label']) !!}
    @else
        {!! Form::label('amount_percent', '-', ['class' => 'col-xs-3 col-sm-1 control-label', 'id' => 'amount_percent']) !!}
    @endif
</div>

<div class="form-group">
    {!! Form::label('paymentmethod', '付款方式:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::select('paymentmethod', array('支票' => '支票', '贷记' => '贷记', '电汇' => '电汇', '汇票' => '汇票', '现金' => '现金', '银行卡' => '银行卡', '其他' => '其他'), null, ['class' => 'form-control', 'placeholder' => '付款方式', $attr, $attrdisable]) !!}
    </div>
</div>
--}}








{!! Form::hidden('applicant_id', null, ['class' => 'btn btn-sm']) !!}
{!! Form::hidden('approversetting_id', null, ['class' => 'btn btn-sm']) !!}

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
    {!! Form::submit($submitButtonText, ['class' => $btnclass, 'id' => 'btnSubmit']) !!}
    </div>
</div>
</div>
</div>



