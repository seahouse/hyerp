<div class="reimb"><div class="form-d">
<div class="form-group">
    {!! Form::label('reimbursementtype_id', '报销类型:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::select('reimbursementtype_id', $reimbursementtypeList, null, ['class' => 'form-control', $attr, $attrdisable]) !!}
    {!! Form::hidden('numberpre', 0, ['class' => 'btn btn-sm', 'id' => 'numberpre']) !!}
    </div>
</div>


<div class="form-group">
    {!! Form::label('date', '申请日期:', ['for' => 'date', 'class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::date('date', $date, ['class' => 'form-control', $attr]) !!}
    {{--
    {!! Form::date('date', $date, ['class' => 'form-control', $attr]) !!}
    --}}
    </div>
</div>

<div class="form-group">
    {!! Form::label('number', '报销编号:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::text('number', null, ['class' => 'form-control', 'placeholder' => '自动生成，不用填写', 'readonly', $attr]) !!}
    </div>
</div>

{{--
<div class="form-group">
    {!! Form::label('amount', '报销金额:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
    {!! Form::text('amount', $amount, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('customer_name', '客户:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
    {!! Form::text('customer_name', $customer_name, ['class' => 'form-control', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectCustomerModal']) !!}
    {!! Form::hidden('customer_id', 0, ['class' => 'btn btn-sm', 'id' => 'customer_id']) !!}
    @if (isset($reimbursement->customer_hxold->name)) 
        {!! Form::hidden('customer_name2', $reimbursement->customer_hxold->name, ['class' => 'btn btn-sm', 'id' => 'customer_name2']) !!}
    @else
        {!! Form::hidden('customer_name2', null, ['class' => 'btn btn-sm', 'id' => 'customer_name2']) !!}
    @endif
    </div>
</div>

<div class="form-group">
    {!! Form::label('contacts', '客户联系人:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
    {!! Form::text('contacts', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('contactspost', '客户联系人职务:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
    {!! Form::text('contactspost', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('order_number', '对应订单:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
    {!! Form::text('order_number', $order_number, ['class' => 'form-control', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectOrderModal']) !!}
    {!! Form::hidden('order_id', 0, ['class' => 'btn btn-sm', 'id' => 'order_id']) !!}
    @if (isset($reimbursement->order_hxold->number)) 
        {!! Form::hidden('order_number2', $reimbursement->order_hxold->number, ['class' => 'btn btn-sm', 'id' => 'order_number2']) !!}
    @else
        {!! Form::hidden('order_number2', null, ['class' => 'btn btn-sm', 'id' => 'order_number2']) !!}
    @endif
    </div>
</div>
--}}

<div class="form-group">
    {!! Form::label('descrip', '其他说明:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::text('descrip', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

@if (isset($reimbursement))
    @foreach ($reimbursement->reimbursementtravels as $reimbursementtravel)
    <p class="bannerTitle">出差时间段明细({{$reimbursementtravel->seq}})</p>
    <div class="form-group">
        {!! Form::label('datego', '出差去日:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
        <div class='col-xs-8 col-sm-10'>
        {!! Form::date('datego', $reimbursementtravel->datego, ['class' => 'form-control', $attr]) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('dateback', '出差回日:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
        <div class='col-xs-8 col-sm-10'>
        {!! Form::date('dateback', $reimbursementtravel->dateback, ['class' => 'form-control', $attr]) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('descrip', '地点及事由:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
        <div class='col-xs-8 col-sm-10'>
        {!! Form::text('descrip', $reimbursementtravel->descrip, ['class' => 'form-control', $attr]) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('customer_name', '客户:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
        <div class='col-xs-8 col-sm-10'>
{{--        
        {!! Form::text('customer_name', null, ['class' => 'form-control', $attr]) !!} 
        {!! Form::hidden('customer_id', 0, ['class' => 'btn btn-sm', 'id' => 'customer_id']) !!}
--}}
        @if (isset($reimbursementtravel->customer_hxold->name))
            {!! Form::text('customer_name', $reimbursementtravel->customer_hxold->name, ['class' => 'form-control', $attr]) !!}
        @else
            {!! Form::text('customer_name', null, ['class' => 'form-control', $attr]) !!}
        @endif
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('contacts', '客户联系人:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
        <div class='col-xs-8 col-sm-10'>
        {!! Form::text('contacts', $reimbursementtravel->contacts, ['class' => 'form-control', $attr]) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('contactspost', '客户联系人职务:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
        <div class='col-xs-8 col-sm-10'>
        {!! Form::text('contactspost', $reimbursementtravel->contactspost, ['class' => 'form-control', $attr]) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('order_number', '对应订单:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
        <div class='col-xs-8 col-sm-10'>
{{--  
        {!! Form::text('travel_1_order_number', null, ['class' => 'form-control', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectOrderModal', 'data-name' => 'travel_1_order_number', 'data-id' => 'travel_1_order_id']) !!}
        {!! Form::hidden('travel_1_order_id', 0, ['class' => 'btn btn-sm', 'id' => 'travel_1_order_id']) !!}
--}}
        @if (isset($reimbursementtravel->order_hxold->number)) 
             {!! Form::text('order_number', $reimbursementtravel->order_hxold->number . ' | ' . str_limit($reimbursementtravel->order_hxold->descrip, 16), ['class' => 'form-control', $attr]) !!}
        @else
            {!! Form::text('order_number', null, ['class' => 'form-control', $attr]) !!}
        @endif
        </div>
    </div>
    @endforeach
@else
<p class="bannerTitle">出差时间段明细(1)</p>

<div class="form-group">
    {!! Form::label('travel_1_datego', '出差去日:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::date('travel_1_datego', $travel_1_datego, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('travel_1_dateback', '出差回日:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::date('travel_1_dateback', $travel_1_dateback, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('travel_1_descrip', '地点及事由:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::text('travel_1_descrip', null, ['class' => 'form-control', 'placeholder' => '请输入这个时间段的出差地点（必填）', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('travel_1_customer_name', '客户:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
{{--    {!! Form::select('customer_name', array('0' => '--请选择--') + json_decode($custinfoList, true), null, ['class' => 'form-control', $attr]) !!} --}}
    {!! Form::text('travel_1_customer_name', $travel_1_customer_name, ['class' => 'form-control', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectCustomerModal', 'data-name' => 'travel_1_customer_name', 'data-id' => 'travel_1_customer_id']) !!}
    {!! Form::hidden('travel_1_customer_id', 0, ['class' => 'btn btn-sm', 'id' => 'travel_1_customer_id']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('travel_1_contacts', '客户联系人:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::text('travel_1_contacts', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('travel_1_contactspost', '客户联系人职务:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::text('travel_1_contactspost', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('travel_1_order_number', '对应订单:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::text('travel_1_order_number', $travel_1_order_number, ['class' => 'form-control', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectOrderModal', 'data-name' => 'travel_1_order_number', 'data-id' => 'travel_1_order_id', 'data-customerid' => 'travel_1_customer_id']) !!}
    {!! Form::hidden('travel_1_order_id', 0, ['class' => 'btn btn-sm', 'id' => 'travel_1_order_id']) !!}
    </div>
</div>

<div id="travelMore">
</div>
{{--{!! Form::button('+增加明细', ['class' => 'btn btn-sm', 'id' => 'btnAddTravel']) !!}--}}
<a href="javascript:void(0);" class="bannerTitle addMore" id="btnAddTravel">+增加明细</a>
@endif

<hr>

{{--
<div class="form-group">
    {!! Form::label('datego', '出差去日:', ['class' => 'col-sm-2 control-label', 'id' => 'lbldatego']) !!}
    <div class='col-sm-10'>
    {!! Form::date('datego', $datego, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('dateback', '出差回日:', ['class' => 'col-sm-2 control-label', 'id' => 'lbldateback']) !!}
    <div class='col-sm-10'>
    {!! Form::date('dateback', $dateback, ['class' => 'form-control', $attr]) !!}
    </div>
</div>
--}}

{{--
<div class="form-group">
    {!! Form::label('mealamount', '伙食补贴:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
    {!! Form::text('mealamount', $mealamount, ['class' => 'form-control', $attr]) !!}
    </div>
</div>
--}}

{{--
<div class="form-group">
    {!! Form::label('ticketamount', '车船费:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
    {!! Form::text('ticketamount', $ticketamount, ['class' => 'form-control', $attr]) !!}
    </div>
</div>
--}}

<div class="form-group">
    {!! Form::label('amountAirfares', '飞机票金额:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::text('amountAirfares', $amountAirfares, ['class' => 'form-control', 'placeholder' => '本次差旅飞机票总额', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('amountTrain', '火车票金额:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-sm-10 col-xs-8'>
    {!! Form::text('amountTrain', $amountTrain, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('amountTaxi', '出租车金额:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-sm-10 col-xs-8'>
    {!! Form::text('amountTaxi', $amountTaxi, ['class' => 'form-control', 'placeholder' => '所有出租车费用', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('amountOtherTicket', '其他交通金额:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-sm-10 col-xs-8'>
    {!! Form::text('amountOtherTicket', $amountOtherTicket, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('stayamount', '住宿费:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-sm-10 col-xs-8'>
    {!! Form::text('stayamount', $stayamount, ['class' => 'form-control', 'placeholder' => '住宿费合计', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('otheramount', '其他费用:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-sm-10 col-xs-8'>
    {!! Form::text('otheramount', $otheramount, ['class' => 'form-control', 'placeholder' => '文印费、退票费、订票费等', $attr]) !!}
    </div>
</div>


<div class="form-group">
    {!! Form::label('images', '图片:', ['class' => 'col-sm-2 control-label']) !!}
    {!! Form::button('+', ['class' => 'btn btn-sm', 'id' => 'btnSelectImage']) !!}
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
<!--             <div class="col-xs-6 col-md-3">
                <div class="thumbnail">
                    <img src="/images/001.jpg" width="100" height="100" />
                </div>
            </div>
            <div class="col-xs-6 col-md-3">
                <div class="thumbnail">
                    <img src="/images/002.jpg" width="100" height="100" />
                </div>
            </div>
            <div class="col-xs-6 col-md-3">
                <div class="thumbnail">
                    <img src="/images/003.jpg" width="100" height="100" />
                </div>
            </div> -->
        </div>
    <!-- <input type="file" accept="image/*"> -->

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




