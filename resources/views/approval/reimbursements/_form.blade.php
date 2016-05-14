<div class="form-group">
    {!! Form::label('reimbursementtype_id', '报销类型:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
    {!! Form::select('reimbursementtype_id', $reimbursementtypeList, null, ['class' => 'form-control', $attr]) !!}
    {!! Form::hidden('numberpre', 0, ['class' => 'btn btn-sm', 'id' => 'numberpre']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('date', '申请日期:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
    {!! Form::date('date', $date, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('number', '报销编号:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
    {!! Form::text('number', null, ['class' => 'form-control', 'placeholder' => '自动生成，不用填写', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('amount', '报销金额:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
    {!! Form::text('amount', $amount, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('customer_name', '客户:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
{{--    {!! Form::select('customer_name', array('0' => '--请选择--') + json_decode($custinfoList, true), null, ['class' => 'form-control', $attr]) !!} --}}
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

<div class="form-group">
    {!! Form::label('descrip', '明细说明:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
    {!! Form::text('descrip', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

@if (isset($reimbursement))
    @foreach ($reimbursement->reimbursementtravels as $reimbursementtravel)
    <p class="bg-info">出差时间段明细({{$reimbursementtravel->seq}})</p>
    <div class="form-group">
        {!! Form::label('datego', '出差去日:', ['class' => 'col-sm-2 control-label']) !!}
        <div class='col-sm-10'>
        {!! Form::date('datego', $reimbursementtravel->datego, ['class' => 'form-control', $attr]) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('dateback', '出差回日:', ['class' => 'col-sm-2 control-label']) !!}
        <div class='col-sm-10'>
        {!! Form::date('dateback', $reimbursementtravel->dateback, ['class' => 'form-control', $attr]) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('descrip', '地点及事由:', ['class' => 'col-sm-2 control-label']) !!}
        <div class='col-sm-10'>
        {!! Form::text('descrip', $reimbursementtravel->descrip, ['class' => 'form-control', $attr]) !!}
        </div>
    </div>
    @endforeach
@else
<p class="bg-info">出差时间段明细(1)</p>

<div class="form-group">
    {!! Form::label('travel_1_datego', '出差去日:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
    {!! Form::date('travel_1_datego', $travel_1_datego, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('travel_1_dateback', '出差回日:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
    {!! Form::date('travel_1_dateback', $travel_1_dateback, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('travel_1_descrip', '地点及事由:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
    {!! Form::text('travel_1_descrip', null, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

<div id="travelMore">
</div>

{!! Form::button('+增加明细', ['class' => 'btn btn-sm', 'id' => 'btnAddTravel']) !!}
@endif

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

<div class="form-group">
    {!! Form::label('mealamount', '伙食补贴:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
    {!! Form::text('mealamount', $mealamount, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('ticketamount', '车船费:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
    {!! Form::text('ticketamount', $ticketamount, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('stayamount', '住宿费:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
    {!! Form::text('stayamount', $stayamount, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('otheramount', '其他费用:', ['class' => 'col-sm-2 control-label']) !!}
    <div class='col-sm-10'>
    {!! Form::text('otheramount', $otheramount, ['class' => 'form-control', $attr]) !!}
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
    {!! Form::submit($submitButtonText, ['class' => $btnclass]) !!}
    </div>
</div>






