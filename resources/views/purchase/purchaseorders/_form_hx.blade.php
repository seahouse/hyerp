<div class="form-group">
    {!! Form::label('number', '采购订单编号:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('number', null, ['class' => 'form-control', 'readonly', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('companyname', '采购公司:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('companyname', null, ['class' => 'form-control', 'readonly', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('applicant', '申请人:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        @if (isset($purchaseorder->applicant))
            {!! Form::text('applicant', $purchaseorder->applicant->name, ['class' => 'form-control', 'readonly', $attr]) !!}
        @else
            {!! Form::text('applicant', null, ['class' => 'form-control', 'readonly', $attr]) !!}
        @endif
    </div>
</div>

<div class="form-group">
    {!! Form::label('custinfo_name', '供应商名称:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('custinfo_name', null, ['class' => 'form-control', 'readonly', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('supplier_contact_name', '供应商联系人:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('supplier_contact_name', null, ['class' => 'form-control', 'readonly', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('supplier_contact_phonenumber', '联系电话:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('supplier_contact_phonenumber', null, ['class' => 'form-control', 'readonly', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('supplier_contact_phonenumber2', '联系人手机:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('supplier_contact_phonenumber2', null, ['class' => 'form-control', 'readonly', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('project_name', '对应项目:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        @if (isset($purchaseorder->sohead))
            {!! Form::text('project_name', $purchaseorder->sohead->number . '|' . $purchaseorder->sohead->custinfo_name . '|' .$purchaseorder->sohead->descrip, ['class' => 'form-control', 'readonly', $attr]) !!}
        @else
            {!! Form::text('project_name', null, ['class' => 'form-control', 'readonly', $attr]) !!}
        @endif
    </div>
</div>

<div class="form-group">
    {!! Form::label('orderdate', '合同签订日期:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('orderdate', \Carbon\Carbon::parse($purchaseorder->orderdate)->toDateString(), ['class' => 'form-control', $attr]) !!}
    </div>
</div>

@can('purchase_purchaseorder_viewamount')
    <div class="form-group">
        {!! Form::label('amount', '合同金额:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
        <div class='col-xs-8 col-sm-10'>
            {!! Form::text('amount', null, ['class' => 'form-control', $attr]) !!}
        </div>
    </div>
@endcan

<div class="form-group">
    {!! Form::label('operator', '合同经办人:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        @if (isset($purchaseorder->operator))
            {!! Form::text('operator', $purchaseorder->operator->name, ['class' => 'form-control', $attr]) !!}
        @else
            {!! Form::text('operator', null, ['class' => 'form-control', 'readonly', $attr]) !!}
        @endif
    </div>
</div>

<div class="form-group">
    {!! Form::label('agreedarrivaldate', '约定到货日期:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('agreedarrivaldate', \Carbon\Carbon::parse($purchaseorder->agreedarrivaldate)->toDateString(), ['class' => 'form-control', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('paymentmethod', '付款方式:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        @if ($purchaseorder->paymentmethod = 0)
            {!! Form::text('paymentmethod', '现金', ['class' => 'form-control', $attr]) !!}
        @elseif ($purchaseorder->paymentmethod = 1)
            {!! Form::text('paymentmethod', '支票', ['class' => 'form-control', $attr]) !!}
        @elseif ($purchaseorder->paymentmethod = 2)
            {!! Form::text('paymentmethod', '转账', ['class' => 'form-control', $attr]) !!}
        @elseif ($purchaseorder->paymentmethod = 3)
            {!! Form::text('paymentmethod', '汇票', ['class' => 'form-control', $attr]) !!}
        @endif
    </div>
</div>

<div class="form-group">
    {!! Form::label('arrival', '到货地:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('arrival', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('type', '采购类型:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('type', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('technicalspecification', '技术规范书:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {{--{!! Form::text('technicalspecification', null, ['class' => 'form-control', $attr]) !!}--}}
        <a  href="{!! config('custom.hxold.purchase_businesscontract_webdir') . $purchaseorder->id . '/' . $purchaseorder->technicalspecification !!}" target="_blank" id="showPdf">{{ $purchaseorder->technicalspecification }}</a>
    </div>
</div>

<div class="form-group">
    {!! Form::label('technicalprotocol', '技术协议:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {{--{!! Form::text('technicalspecification', null, ['class' => 'form-control', $attr]) !!}--}}
        <a  href="{!! config('custom.hxold.purchase_businesscontract_webdir') . $purchaseorder->id . '/' . $purchaseorder->technicalprotocol !!}" target="_blank">{{ $purchaseorder->technicalprotocol }}</a>
    </div>
</div>

@can('purchase_purchaseorder_businesscontract')
    <div class="form-group">
        {!! Form::label('businesscontract', '商务合同:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
        <div class='col-xs-8 col-sm-10'>
            {{--{!! Form::text('technicalspecification', null, ['class' => 'form-control', $attr]) !!}--}}
            <a  href="{!! config('custom.hxold.purchase_businesscontract_webdir') . $purchaseorder->id . '/' . $purchaseorder->businesscontract !!}" target="_blank">{{ $purchaseorder->businesscontract }}</a>
        </div>
    </div>
@endcan

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit($submitButtonText, ['class' => $btnclass, 'id' => 'btnSubmit']) !!}
    </div>
</div>
