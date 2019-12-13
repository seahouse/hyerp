<div class="reimb"><div class="form-d">

<div class="form-group">
    {!! Form::label('purchasecompany_id', '采购所属公司:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::select('purchasecompany_id', $companyList, null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable]) !!}
    </div>
</div>


        <div class="form-group">
            {!! Form::label('submitdepart', '提交部门:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::select('submitdepart', array('工艺一室' => '工艺一室', '工艺二室' => '工艺二室', '工艺三室' => '工艺三室', '电控室' => '电控室'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable]) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('arrivaldate', '要求采购到位日期:', ['for' => 'date', 'class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::date('arrivaldate', $arrivaldate, ['class' => 'form-control', $attr]) !!}
            </div>
        </div>

@if (isset($paymentrequest))

<div class="form-group">
    {!! Form::label('pohead_descrip', '对应工程名称:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    @if (isset($paymentrequest->purchaseorder_hxold->sohead->custinfo->name)) 
         {!! Form::textarea('pohead_descrip', $paymentrequest->purchaseorder_hxold->sohead->custinfo->name . ' | ' . $paymentrequest->purchaseorder_hxold->sohead->descrip, ['class' => 'form-control', $attr, 'rows' => 3]) !!}
    @else
        {!! Form::textarea('pohead_descrip', null, ['class' => 'form-control', $attr, 'rows' => 3]) !!}
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


@else

<div class="form-group">
    {!! Form::label('project_name', '项目名称:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::text('project_name', $project_name, ['class' => 'form-control', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectProjectModal', 'data-name' => 'project_name', 'data-id' => 'sohead_id']) !!}
    {!! Form::hidden('sohead_id', 0, ['class' => 'btn btn-sm', 'id' => 'sohead_id']) !!}
    </div>
</div>

            <div class="form-group">
                {!! Form::label('sohead_number', '项目合同编号:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                <div class='col-xs-8 col-sm-10'>
                    {!! Form::text('sohead_number', null, ['class' => 'form-control', 'readonly', $attr]) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('sohead_salesmanager', '订单所属销售经理:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                <div class='col-xs-8 col-sm-10'>
                    {!! Form::text('sohead_salesmanager', null, ['class' => 'form-control', 'readonly', $attr]) !!}
                </div>
            </div>



        <div id="items_excel">

        </div>

            <p class="bannerTitle">明细(1)</p>

        <div name="container_item">
            <div class="form-group">
                {!! Form::label('item_name', '商品名称:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                <div class='col-xs-8 col-sm-10'>
                    {!! Form::text('item_name', $item_name, ['class' => 'form-control', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectItemModal', 'data-name' => 'project_name', 'data-num' => '1', 'id' => 'item_name_1']) !!}
                    {!! Form::hidden('item_id', 0, ['class' => 'btn btn-sm', 'id' => 'item_id_1']) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('item_spec', '商品型号:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                <div class='col-xs-8 col-sm-10'>
                    {!! Form::text('item_spec', null, ['class' => 'form-control', 'readonly', $attr, 'id' => 'item_spec_1']) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('item_type', '商品类别:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                <div class='col-xs-8 col-sm-10'>
                    {!! Form::text('item_type', null, ['class' => 'form-control', 'readonly', $attr, 'id' => 'item_type_1']) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('unit', '单位:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                <div class='col-xs-8 col-sm-10'>
                    {!! Form::text('unit', null, ['class' => 'form-control', 'readonly', $attr, 'id' => 'unit_1']) !!}
                </div>
            </div>

            {{--<div class="form-group">--}}
                {{--{!! Form::label('size', '尺寸:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}--}}
                {{--<div class='col-xs-8 col-sm-10'>--}}
                    {{--{!! Form::text('size', null, ['class' => 'form-control', 'placeholder' => '', $attr, 'id' => 'size_1']) !!}--}}
                {{--</div>--}}
            {{--</div>--}}

            {{--<div class="form-group">--}}
                {{--{!! Form::label('material', '材质:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}--}}
                {{--<div class='col-xs-8 col-sm-10'>--}}
                    {{--{!! Form::text('material', null, ['class' => 'form-control', 'placeholder' => '', $attr, 'id' => 'material_1']) !!}--}}
                {{--</div>--}}
            {{--</div>--}}

            {{--<div class="form-group">--}}
                {{--{!! Form::label('unitprice', '单价（可不填）:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}--}}
                {{--<div class='col-xs-8 col-sm-10'>--}}
                    {{--{!! Form::text('unitprice', null, ['class' => 'form-control', 'placeholder' => '', $attr, 'id' => 'unitprice_1']) !!}--}}
                {{--</div>--}}
            {{--</div>--}}

            <div class="form-group">
                {!! Form::label('quantity', '数量:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                <div class='col-xs-8 col-sm-10'>
                    {!! Form::text('quantity', null, ['class' => 'form-control', 'placeholder' => '', $attr, 'id' => 'quantity_1']) !!}
                </div>
                {{--<div class='col-xs-3 col-sm-2'>--}}
                    {{--{!! Form::select('unit_id', $unitList_hxold, null, ['class' => 'form-control', 'placeholder' => '--默认--']) !!}--}}
                {{--</div>--}}
            </div>

            {{--<div class="form-group">--}}
                {{--{!! Form::label('weight', '重量（吨）:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}--}}
                {{--<div class='col-xs-8 col-sm-10'>--}}
                    {{--{!! Form::text('weight', null, ['class' => 'form-control', 'placeholder' => '', $attr, 'id' => 'weight_1']) !!}--}}
                {{--</div>--}}
            {{--</div>--}}

            <div class="form-group">
                {!! Form::label('descrip', '商品说明:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                <div class='col-xs-8 col-sm-10'>
                    {!! Form::text('descrip', null, ['class' => 'form-control', 'placeholder' => '', $attr, 'id' => 'descrip_1']) !!}
                </div>
            </div>
        </div>


            <div id="itemMore">
            </div>
            {{--{!! Form::button('+增加明细', ['class' => 'btn btn-sm', 'id' => 'btnAddTravel']) !!}--}}
            <a href="javascript:void(0);" class="bannerTitle addMore" id="btnAddItem">+增加明细</a>








@endif
        {!! Form::hidden('items_string', null, ['id' => 'items_string']) !!}
        {!! Form::hidden('items_string2', null, ['id' => 'items_string2']) !!}

{{--<div class="form-group">--}}
    {{--{!! Form::label('totalprice', '总价（元）:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}--}}
    {{--<div class='col-xs-8 col-sm-10'>--}}
        {{--{!! Form::text('totalprice', null, ['class' => 'form-control', 'placeholder' => '请输入', $attr]) !!}--}}
    {{--</div>--}}
{{--</div>--}}




@if (isset($paymentrequest))

<div class="form-group">
    {!! Form::label('created_at', '发起时间:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
        {!! Form::text('created_at', $paymentrequest->created_at, ['class' => 'form-control', $attr]) !!}
    </div>
</div>

@if ($paymentrequest->approversetting_id === 0)
    @if ($paymentrequest->paymentrequestapprovals->count())
        <div class="form-group">
            {!! Form::label('last_approval_created_at', '审批时间:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text('last_approval_created_at', $paymentrequest->paymentrequestapprovals->last()->created_at, ['class' => 'form-control', $attr]) !!}
            </div>
        </div>
    @endif
@endif

@else

@endif


        {{--<div class="form-group">--}}
            {{--{!! Form::label('detailuse', '采购物品详细用途:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}--}}
            {{--<div class='col-xs-8 col-sm-10'>--}}
                {{--{!! Form::textarea('detailuse', null, ['class' => 'form-control', $attr, 'rows' => 3]) !!}--}}
            {{--</div>--}}
        {{--</div>--}}

        <div class="form-group">
            {!! Form::label('files', '上传技术规范书（到钉钉审批）:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}

            <div class='col-xs-8 col-sm-10'>
                @if (isset($issuedrawing))
                    @foreach ($issuedrawing->drawingattachments() as $drawingattachment)
                        <a href="{!! URL($drawingattachment->path) !!}" target="_blank" id="showPaymentnode">{{ $drawingattachment->filename }}</a> <br>
                    @endforeach
                @else
{{--                    {!! Form::file('files[]', ['multiple']) !!}--}}
                    {!! Form::button('+', ['class' => 'btn btn-sm', 'id' => 'uploadAttach']) !!}
                    <div id="lblFiles">
                    </div>
                    {!! Form::hidden('files_string', null, ['id' => 'files_string']) !!}
                @endif
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('techspecification', '上传技术规范书（到ERP）:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}

            <div class='col-xs-8 col-sm-10'>
                @if (isset($issuedrawing))
                    @foreach ($issuedrawing->drawingattachments() as $drawingattachment)
                        <a href="{!! URL($drawingattachment->path) !!}" target="_blank" id="showPaymentnode">{{ $drawingattachment->filename }}</a> <br>
                    @endforeach
                @else
                    {!! Form::file('techspecifications[]', ['multiple']) !!}
                @endif
            </div>
        </div>

{{--<div class="form-group">--}}
    {{--{!! Form::label('images', '上传图片:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}--}}
    {{----}}
    {{--<div class='col-xs-8 col-sm-10'>--}}
        {{--<div class="row" id="previewimage">--}}
        {{--</div>--}}
        {{--@if (isset($paymentrequest))--}}
            {{--<div class="row" id="previewimage2">--}}
                {{--@foreach ($paymentrequest->paymentrequestimages() as $paymentrequestimage)--}}
                    {{--<div class="col-xs-6 col-md-3">--}}
                        {{--<div class="thumbnail">--}}
                            {{--<img src="{!! $paymentrequestimage->path !!}" />--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--@endforeach--}}
            {{--</div>--}}
        {{--@else--}}
            {{--@if (Agent::isDesktop())--}}
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
</div>
</div>



