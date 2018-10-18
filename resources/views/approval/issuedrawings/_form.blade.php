<div class="reimb"><div class="form-d">

<div class="form-group">
    {!! Form::label('designdepartment', '设计部门:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::select('designdepartment', array('工艺一室' => '工艺一室', '工艺二室' => '工艺二室', '电控室' => '电控室'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable]) !!}
    </div>
</div>



@if (isset($issuedrawing))
<div class="form-group">
    {!! Form::label('project_name', '所属项目:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    @if (isset($issuedrawing->sohead_hxold->descrip))
        {!! Form::text('project_name', $issuedrawing->sohead_hxold->descrip, ['class' => 'form-control', $attr]) !!}
    @else
        {!! Form::text('project_name', null, ['class' => 'form-control', $attr]) !!}
    @endif
    </div>
</div>


<div class="form-group">
    {!! Form::label('sohead_number', '项目编号:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    @if (isset($issuedrawing->sohead_hxold->number))
         {!! Form::text('sohead_number', $issuedrawing->sohead_hxold->number, ['class' => 'form-control', $attr]) !!}
    @else
        {!! Form::text('sohead_number', null, ['class' => 'form-control', $attr]) !!}
    @endif
    </div>
</div>


<div class="form-group">
    {!! Form::label('drawingchecker', '图纸校核人:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    @if (isset($issuedrawing->drawingchecker->name))
         {!! Form::text('drawingchecker', $issuedrawing->drawingchecker->name, ['class' => 'form-control', $attr]) !!}
    @else
        {!! Form::text('drawingchecker', null, ['class' => 'form-control', $attr]) !!}
    @endif
    </div>
</div>


{{--
<div class="form-group">
    {!! Form::label('pohead_amount_paid', '已付金额:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-5 col-sm-9'>
    @if (isset($paymentrequest->purchaseorder_hxold->amount_paid)) 
         {!! Form::text('pohead_amount', $paymentrequest->purchaseorder_hxold->amount_paid, ['class' => 'form-control', $attr]) !!}
    @else
        {!! Form::text('pohead_amount', null, ['class' => 'form-control', $attr]) !!}
    @endif
    </div>
    @if (isset($paymentrequest->purchaseorder_hxold->amount_paid) and isset($paymentrequest->purchaseorder_hxold->amount) and $paymentrequest->purchaseorder_hxold->amount > 0.0)
        {!! Form::label('amount_paid_percent', number_format($paymentrequest->purchaseorder_hxold->amount_paid / $paymentrequest->purchaseorder_hxold->amount * 100.0, 2, '.', '') . '%', ['class' => 'col-xs-3 col-sm-1 control-label']) !!}
    @else
        {!! Form::label('amount_paid_percent', '-', ['class' => 'col-xs-3 col-sm-1 control-label', 'id' => 'amount_paid_percent']) !!}
    @endif
</div>

<div class="form-group">
    {!! Form::label('pohead_amount_ticketed', '已开票金额:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-5 col-sm-9'>
    @if (isset($paymentrequest->purchaseorder_hxold->amount_ticketed)) 
         {!! Form::text('pohead_amount_ticketed', $paymentrequest->purchaseorder_hxold->amount_ticketed, ['class' => 'form-control', $attr]) !!}
    @else
        {!! Form::text('pohead_amount_ticketed', null, ['class' => 'form-control', $attr]) !!}
    @endif
    </div>
    @if (isset($paymentrequest->purchaseorder_hxold->amount_ticketed) and isset($paymentrequest->purchaseorder_hxold->amount) and $paymentrequest->purchaseorder_hxold->amount > 0.0)
        {!! Form::label('amount_ticketed_percent', number_format($paymentrequest->purchaseorder_hxold->amount_ticketed / $paymentrequest->purchaseorder_hxold->amount * 100.0, 2, '.', '') . '%', ['class' => 'col-xs-3 col-sm-1 control-label']) !!}
    @else
        {!! Form::label('amount_ticketed_percent', '-', ['class' => 'col-xs-3 col-sm-1 control-label', 'id' => 'amount_ticketed_percent']) !!}
    @endif
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
--}}


@else
<div class="form-group">
    {!! Form::label('project_name', '所属项目:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::text('project_name', $project_name, ['class' => 'form-control', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectProjectModal', 'data-name' => 'project_name', 'data-id' => 'sohead_id']) !!}
    {!! Form::hidden('sohead_id', 0, ['class' => 'btn btn-sm', 'id' => 'sohead_id']) !!}
    </div>
</div>

            <div class="form-group">
                {!! Form::label('sohead_number', '项目编号:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                <div class='col-xs-8 col-sm-10'>
                    {!! Form::text('sohead_number', null, ['class' => 'form-control', 'readonly', $attr]) !!}
                </div>
            </div>


<div class="form-group">
    {!! Form::label('drawingchecker', '图纸校核人:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::text('drawingchecker', $drawingchecker, ['class' => 'form-control', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectDrawingcheckerModal', 'data-name' => 'drawingchecker', 'data-id' => 'pohead_id', 'data-soheadid' => 'sohead_id', 'data-poheadamount' => 'pohead_amount']) !!}
    {!! Form::hidden('drawingchecker_id', 0, ['class' => 'btn btn-sm', 'id' => 'drawingchecker_id']) !!}
    @if (isset($reimbursement->customer_hxold->name))
        {!! Form::hidden('customer_name2', $reimbursement->customer_hxold->name, ['class' => 'btn btn-sm', 'id' => 'customer_name2']) !!}
    @else
        {!! Form::hidden('customer_name2', null, ['class' => 'btn btn-sm', 'id' => 'customer_name2']) !!}
    @endif
    </div>
</div>









@endif


<div class="form-group">
    {!! Form::label('overview', '制作概述:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('overview', null, ['class' => 'form-control', 'placeholder' => '（简述制作的主要设备及内容）', $attr]) !!}
    </div>
</div>

        <div class="form-group">
            {!! Form::label('cabinetname', '柜体名称:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}
            <div class='col-xs-5 col-sm-8'>
                {!! Form::text('cabinetname', null, ['class' => 'form-control', 'placeholder' => '电气部填写', $attr]) !!}
            </div>
            <div class='col-xs-3 col-sm-2'>
                {!! Form::text('cabinetquantity', null, ['class' => 'form-control', 'placeholder' => '数量', $attr]) !!}
            </div>
        </div>

<div class="form-group">
    {!! Form::label('tonnage', '吨位（吨）:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::text('tonnage', null, ['class' => 'form-control', 'placeholder' => '请输入本次制作内容的总吨位', 'id' => 'amount', $attr]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('productioncompany', '制作公司:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::select('productioncompany', array('无锡生产中心' => '无锡生产中心', '苏州生产中心' => '苏州生产中心', '泰州生产中心' => '泰州生产中心', '胶州生产中心' => '胶州生产中心', '无锡电气生产部' => '无锡电气生产部'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable]) !!}
    </div>
</div>

        <div class="form-group">
            {!! Form::label('materialsupplier', '材料供应方:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::select('materialsupplier', array('华星东方' => '华星东方'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable]) !!}
            </div>
        </div>

<div class="form-group">
    {!! Form::label('requestdeliverydate', '要求发货日:', ['for' => 'date', 'class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::date('requestdeliverydate', $requestdeliverydate, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

        <div class="form-group">
            {!! Form::label('drawingcount', '图纸份数:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text('drawingcount', null, ['class' => 'form-control', 'placeholder' => '下发图纸份数', 'id' => 'amount', $attr]) !!}
            </div>
        </div>

@if (isset($issuedrawing))


<div class="form-group">
    {!! Form::label('created_at', '发起时间:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('created_at', $issuedrawing->created_at, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

@if ($issuedrawing->approversetting_id === 0)
    @if ($issuedrawing->paymentrequestapprovals->count())
        <div class="form-group">
            {!! Form::label('last_approval_created_at', '审批时间:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text('last_approval_created_at', $issuedrawing->paymentrequestapprovals->last()->created_at, ['class' => 'form-control', $attr]) !!}
            </div>
        </div>
    @endif
@endif

@else

@endif


<div class="form-group">
    {!! Form::label('drawingattachment', '目录上传，图纸邮寄:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}

    <div class='col-xs-8 col-sm-10'>
        @if (isset($issuedrawing))
            @foreach ($issuedrawing->drawingattachments() as $drawingattachment)
                 <a href="{!! URL($drawingattachment->path) !!}" target="_blank" id="showPaymentnode">{{ $drawingattachment->filename }}</a> <br>
            @endforeach
        @else
            {!! Form::file('drawingattachments[]', ['multiple']) !!}
        @endif
    </div>
</div>

        <div class="form-group">
            {!! Form::label('remark', '备注:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::textarea('remark', null, ['class' => 'form-control', $attr, 'rows' => 3]) !!}
            </div>
        </div>

<div class="form-group">
    {!! Form::label('images', '图纸签收回执:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    
    <div class='col-xs-8 col-sm-10'>
        <div class="row" id="previewimage">
        </div>
        @if (isset($issuedrawing))
            <div class="row" id="previewimage2">
                @foreach ($issuedrawing->images() as $image)
                    <div class="col-xs-6 col-md-3">
                        <div class="thumbnail">
                            <img src="{!! $image->path !!}" />
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            @if (Agent::isDesktop())
                {!! Form::file('images[]', ['multiple']) !!}
            @else
                {!! Form::button('+', ['class' => 'btn btn-sm', 'id' => 'btnSelectImage']) !!}
            @endif            
        @endif

    </div>
</div>

{{--
        {!! Form::file('image_file', array('class' => 'form-control')) !!}
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



