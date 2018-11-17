<div class="reimb"><div class="form-d">

<div class="form-group">
    {!! Form::label('productioncompany', '制作公司:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    {!! Form::select('productioncompany', array('苏州分公司' => '苏州分公司', '泰州分公司' => '泰州分公司', '胶州分公司' => '胶州分公司'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable, 'id' => 'productioncompany']) !!}
    </div>
</div>

        <div class="form-group">
            {!! Form::label('designdepartment', '设计部门:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::select('designdepartment', array('工艺一室－杨青' => '工艺一室－杨青', '工艺二室－丁银红' => '工艺二室－丁银红'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable]) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('paymentreason', '付款事由:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::textarea('paymentreason', null, ['class' => 'form-control', $attr, 'rows' => 3]) !!}
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









        <div id="items_excel">

        </div>

            <p class="bannerTitle">明细(1)</p>

            <div name="container_item">

            <div class="form-group">
                {!! Form::label('project_name', '所属项目:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                <div class='col-xs-8 col-sm-10'>
                    {!! Form::text('project_name', $project_name, ['class' => 'form-control', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectProjectModal', 'data-name' => 'project_name_1', 'data-id' => 'sohead_id_1', 'data-num' => '1', 'id' => 'project_name_1']) !!}
                    {!! Form::hidden('sohead_id', 0, ['class' => 'btn btn-sm', 'id' => 'sohead_id_1']) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('sohead_number', '项目编号:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                <div class='col-xs-8 col-sm-10'>
                    {!! Form::text('sohead_number', null, ['class' => 'form-control', 'readonly', $attr, 'id' => 'sohead_number_1']) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('productionoverview', '制作概述:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}
                <div class='col-xs-8 col-sm-10'>
                    {!! Form::textarea('productionoverview', null, ['class' => 'form-control', $attr, 'rows' => 3, 'id' => 'productionoverview_1']) !!}
                </div>
            </div>

                <div class="form-group">
                    {!! Form::label('tonnage', '吨位:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                    <div class='col-xs-8 col-sm-10'>
                        {!! Form::text('tonnage', null, ['class' => 'form-control', 'placeholder' => '', $attr, 'id' => 'tonnage_1']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('issuedrawing_numbers', '下发图纸审批单号:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                    <div class='col-xs-8 col-sm-10'>
                        {!! Form::text('issuedrawing_numbers', null, ['class' => 'form-control', 'placeholder' => '--点击选择--', 'readonly', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectIssueDrawingsModal', 'data-name' => 'issuedrawing_numbers_1', 'data-id' => 'issuedrawing_values_1', 'data-num' => '1', 'id' => 'issuedrawing_numbers_1']) !!}
                        {!! Form::hidden('issuedrawing_values', null, ['class' => 'btn btn-sm', 'id' => 'issuedrawing_values_1']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('area', '地区:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                    <div class='col-xs-8 col-sm-10'>
                        {!! Form::select('area', array('国内' => '国内', '国外' => '国外'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, 'id' => 'area_1', 'onchange' => 'selectTypeChange(this.dataset.num)', 'data-num' => '1']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('type', '类型:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                    <div class='col-xs-8 col-sm-10'>
                        {!! Form::select('type', array('抛丸' => '抛丸', '油漆' => '油漆', '人工' => '人工', '铆焊' => '铆焊'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, 'id' => 'type_1', 'onchange' => 'selectTypeChange(this.dataset.num)', 'data-num' => '1']) !!}
                    </div>
                </div>
                {!! Form::hidden('unitprice_inputname', 'unitprice_inputname_1', ['class' => 'btn btn-sm', 'id' => 'unitprice_inputname_1']) !!}
                {!! Form::hidden('totalprice_inputname', 'totalprice_inputname_1', ['class' => 'btn btn-sm']) !!}

                <div id="pppaymentitemtypecontainer_1" name="pppaymentitemtypecontainer"></div>









                <div class="form-group">
                    {!! Form::label('images', '上传质检签收单:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}

                    <div class='col-xs-8 col-sm-10'>
                        <div class="row" id="previewimage_1">
                        </div>
                        @if (isset($paymentrequest))
                            <div class="row" id="previewimage2">
                                @foreach ($paymentrequest->paymentrequestimages() as $paymentrequestimage)
                                    <div class="col-xs-6 col-md-3">
                                        <div class="thumbnail">
                                            <img src="{!! $paymentrequestimage->path !!}" />
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            {!! Form::hidden('imagesname', 'images_1', ['class' => 'btn btn-sm', 'id' => 'imagesname_1']) !!}
                            @if (Agent::isDesktop())
                                {!! Form::file('images_1[]', ['multiple', 'id' => 'images_1']) !!}
                            @else
                                {!! Form::button('+', ['class' => 'btn btn-sm', 'id' => 'btnSelectImage', 'value' => '1', 'onclick' => 'selectImage_Mobile(1)']) !!}  {{-- value for num --}}
                                {!! Form::hidden('imagesname_mobile', null, ['class' => 'btn btn-sm', 'id' => 'imagesname_mobile_1']) !!}
                            @endif
                        @endif

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


        <div class="form-group">
            {!! Form::label('invoicingsituation', '发票开具情况:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text('invoicingsituation', null, ['class' => 'form-control', 'placeholder' => '请输入', $attr]) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('totalpaid', '该加工单已付款总额:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text('totalpaid', null, ['class' => 'form-control', 'placeholder' => '请输入', $attr]) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('amount', '本次申请付款总额:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text('amount', null, ['class' => 'form-control', 'placeholder' => '会根据单价明细自动计算', $attr, 'readonly']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('paymentdate', '支付日期:', ['for' => 'date', 'class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::date('paymentdate', $paymentdate, ['class' => 'form-control', $attr]) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('supplier_name', '支付对象:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text('supplier_name', null, ['class' => 'form-control', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectSupplierModal', 'data-name' => 'supplier_name', 'data-id' => 'supplier_id']) !!}
                {!! Form::hidden('supplier_id', 0, ['class' => 'btn btn-sm', 'id' => 'supplier_id']) !!}
            </div>
        </div>








@if (isset($paymentrequest))
<div class="form-group">
    {!! Form::label('supplier_bank', '开户行:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    @if (isset($paymentrequest->vendbank_hxold->bankname)) 
        {!! Form::text('supplier_bank', $paymentrequest->vendbank_hxold->bankname, ['class' => 'form-control', $attr]) !!}
    @else
        {!! Form::text('supplier_bank', null, ['class' => 'form-control', $attr]) !!}
    @endif
    </div>
</div>

<div class="form-group">
    {!! Form::label('supplier_bankaccountnumber', '银行账号:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    <div class='col-xs-8 col-sm-10'>
    @if (isset($paymentrequest->vendbank_hxold->accountnum)) 
        {!! Form::text('supplier_bankaccountnumber', $paymentrequest->vendbank_hxold->accountnum, ['class' => 'form-control', $attr]) !!}
    @else
        {!! Form::text('supplier_bankaccountnumber', null, ['class' => 'form-control', $attr]) !!}
    @endif
    </div>
</div>



@else
            <div class="form-group">
                {!! Form::label('supplier_bank', '开户行:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                <div class='col-xs-8 col-sm-10'>
                    {{--
                        {!! Form::text('supplier_bank', null, ['class' => 'form-control', 'readonly', $attr]) !!}
                    --}}
                    {!! Form::text('supplier_bank', null, ['class' => 'form-control', 'placeholder' => '点击选择', 'readonly', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectSupplierBankModal', 'data-name' => 'supplier_name', 'data-id' => 'supplier_id']) !!}

                    {!! Form::hidden('vendbank_id', 0, ['id' => 'vendbank_id']) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('supplier_bankaccountnumber', '银行账号:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                <div class='col-xs-8 col-sm-10'>
                    {!! Form::text('supplier_bankaccountnumber', null, ['class' => 'form-control', 'readonly', $attr]) !!}
                </div>
            </div>
@endif





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



