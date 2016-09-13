<div class="reimb"><div class="form-d">

<div class="form-group">
    {!! Form::label('suppliertype', '供应商类型:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::select('suppliertype', array('安装公司' => '安装公司', '机务设备类' => '机务设备类', '电气设备类' => '电气设备类', '安装材料类' => '安装材料类', '代理或服务类' => '代理或服务类', '厂部常用类' => '厂部常用类', '其他' => '其他'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('paymenttype', '付款类型:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::select('paymenttype', array('预付款' => '预付款', '进度款' => '进度款', '到货款' => '到货款', '安装结束款' => '安装结束款', '调试运行款' => '调试运行款', '环保验收款' => '环保验收款', '质保金' => '质保金'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr]) !!}
    </div>
</div>


@if (isset($paymentrequest))
<div class="form-group">
    {!! Form::label('supplier_name', '支付对象:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    @if (isset($paymentrequest->supplier_hxold->name))
        {!! Form::text('supplier_name', $paymentrequest->supplier_hxold->name, ['class' => 'form-control', $attr]) !!}
    @else
        {!! Form::text('supplier_name', null, ['class' => 'form-control', $attr]) !!}
    @endif
    </div>
</div>

<div class="form-group">
    {!! Form::label('pohead_number', '采购合同:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    @if (isset($paymentrequest->purchaseorder_hxold->number)) 
         {!! Form::text('pohead_number', $paymentrequest->purchaseorder_hxold->number, ['class' => 'form-control', $attr]) !!}
    @else
        {!! Form::text('pohead_number', null, ['class' => 'form-control', $attr]) !!}
    @endif
    </div>
</div>


<div class="form-group">
    {!! Form::label('pohead_amount', '合同金额:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    @if (isset($paymentrequest->purchaseorder_hxold->amount)) 
         {!! Form::text('pohead_amount', $paymentrequest->purchaseorder_hxold->amount, ['class' => 'form-control', $attr]) !!}
    @else
        {!! Form::text('pohead_amount', null, ['class' => 'form-control', $attr]) !!}
    @endif
    </div>
</div>

<div class="form-group">
    {!! Form::label('pohead_amount_paid', '已付金额:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    @if (isset($paymentrequest->purchaseorder_hxold->amount_paid)) 
         {!! Form::text('pohead_amount', $paymentrequest->purchaseorder_hxold->amount_paid, ['class' => 'form-control', $attr]) !!}
    @else
        {!! Form::text('pohead_amount', null, ['class' => 'form-control', $attr]) !!}
    @endif
    </div>
</div>

<div class="form-group">
    {!! Form::label('pohead_amount_ticketed', '已开票金额:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    @if (isset($paymentrequest->purchaseorder_hxold->amount_ticketed)) 
         {!! Form::text('pohead_amount', $paymentrequest->purchaseorder_hxold->amount_ticketed, ['class' => 'form-control', $attr]) !!}
    @else
        {!! Form::text('pohead_amount', null, ['class' => 'form-control', $attr]) !!}
    @endif
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
@endif

<div class="form-group">
    {!! Form::label('equipmentname', '应付款设备名称:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::text('equipmentname', null, ['class' => 'form-control', 'placeholder' => '请输入本次应付款的大体设备名称', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('descrip', '付款说明:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::textarea('descrip', null, ['class' => 'form-control', 'placeholder' => '按合同已付多少，百分比及发票开具情况说明', $attr, 'rows' => 3]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('amount', '付款总额:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::text('amount', null, ['class' => 'form-control', 'placeholder' => '请输入付款总额（人民币）（必填）', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('paymentmethod', '付款方式:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::select('paymentmethod', array('支票' => '支票', '贷记' => '贷记', '电汇' => '电汇', '汇票' => '汇票', '现金' => '现金', '银行卡' => '银行卡', '其他' => '其他'), null, ['class' => 'form-control', 'placeholder' => '付款方式', $attr]) !!}
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

<div class="form-group">
    {!! Form::label('paymentnodeattachments', '付款节点审批单:', ['class' => 'col-sm-2 control-label']) !!}
    <将在钉钉的下一个版本中支持上传附件>
{{--
    {!! Form::file('paymentnodeattachments[]', ['multiple']) !!}
    {!! Form::button('+', ['class' => 'btn btn-sm', 'id' => 'btnSelectPaymentnodeattachment']) !!}
--}}
    <div class='col-sm-10'>
        <div class="row" id="previewimage">
            @if (isset($reimbursement))
                @foreach ($reimbursement->reimbursementimages as $reimbursementimage)
                    <div class="col-xs-6 col-md-3">
                        <div class="thumbnail">
                            <img src="{!! $reimbursementimage->path !!}" />
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<div class="form-group">
    {!! Form::label('images', '商务合同等必要附件:', ['class' => 'col-sm-2 control-label']) !!}
    <将在钉钉的下一个版本中支持上传附件>
{{--
    {!! Form::button('+', ['class' => 'btn btn-sm', 'id' => 'btnSelectImage']) !!}
--}}
    <div class='col-sm-10'>
        <div class="row" id="previewimage">
            @if (isset($reimbursement))
                @foreach ($reimbursement->reimbursementimages as $reimbursementimage)
                    <div class="col-xs-6 col-md-3">
                        <div class="thumbnail">
                            <img src="{!! $reimbursementimage->path !!}" />
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<div class="form-group">
    {!! Form::label('images', '图片说明:', ['class' => 'col-sm-2 control-label']) !!}
    {!! Form::button('+', ['class' => 'btn btn-sm', 'id' => 'btnSelectImage']) !!}
    <div class='col-sm-10'>
        <div class="row" id="previewimage">
            @if (isset($paymentrequest))
                @foreach ($paymentrequest->reimbursementimages as $reimbursementimage)
                    <div class="col-xs-6 col-md-3">
                        <div class="thumbnail">
                            <img src="{!! $reimbursementimage->path !!}" />
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>






{!! Form::hidden('applicant_id', null, ['class' => 'btn btn-sm']) !!}
{!! Form::hidden('approversetting_id', null, ['class' => 'btn btn-sm']) !!}

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
    {!! Form::submit($submitButtonText, ['class' => $btnclass, 'id' => 'btnSubmit']) !!}
    </div>
</div>
</div>
</div>



