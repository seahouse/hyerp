<div class="reimb"><div class="form-d">

        <div class="form-group">
            {!! Form::label('purchasecompany_id', '采购公司:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::select('purchasecompany_id', $companyList, null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable]) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('project_name', '所属订单:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text('project_name', $project_name, ['class' => 'form-control', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectProjectModal', 'data-name' => 'project_name_1', 'data-id' => 'sohead_id_1', 'data-num' => '1', 'id' => 'project_name_1']) !!}
                {!! Form::hidden('sohead_id', 0, ['class' => 'btn btn-sm', 'id' => 'sohead_id_1']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('sohead_number', '订单编号:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text('sohead_number', null, ['class' => 'form-control', 'readonly', $attr, 'id' => 'sohead_number_1']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('sohead_salesmanager', '订单所属销售经理:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text('sohead_salesmanager', null, ['class' => 'form-control', 'readonly', $attr]) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('projecttype', '项目类型:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::select('projecttype', array('EP项目' => 'EP项目', 'EPC项目' => 'EPC项目'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable]) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('vendordeduction_descrip', '采购是否涉及供应商扣款:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::select('vendordeduction_descrip', array('是，供应商扣款流程已审批完结，并在此流程后关联《供应商扣款》审批单。' => '是，供应商扣款流程已审批完结，并在此流程后关联《供应商扣款》审批单。', '不涉及供应商扣款' => '不涉及供应商扣款'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable]) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('designdept', '订单所属设计部门:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::select('designdept', array('工艺一室' => '工艺一室', '工艺二室' => '工艺二室', '工艺三室' => '工艺三室', '电控室' => '电控室', '不涉及设计部门' => '不涉及设计部门'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable]) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('productiondept', '生产部门:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::select('productiondept', array('无锡生产中心生产' => '无锡生产中心生产', '朗溪生产中心生产' => '朗溪生产中心生产',
                    '许昌生产中心生产' => '许昌生产中心生产', '由外协设备商供货' => '由外协设备商供货', '不涉及生产部门' => '不涉及生产部门'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable]) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('purchasetype', '采购类型:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::select('purchasetype', array('机务材料类' => '机务材料类', '电气材料类' => '电气材料类', '机务设备类' => '机务设备类', '电气设备类' => '电气设备类', '维修费用' => '维修费用',
                    '工具类' => '工具类', '劳务费' => '劳务费', '劳保类' => '劳保类', '办公用品' => '办公用品', '生活用品' => '生活用品'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable]) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('purchasereason', '采购原因:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::select('purchasereason', array('不属我方供货范围，甲方要求增加。' => '不属我方供货范围，甲方要求增加。', '现场材料业主不满意，需更改，现场购买。' => '现场材料业主不满意，需更改，现场购买。', '现场施工质量甲方不满意，返工需增加部分现场购买。' => '现场施工质量甲方不满意，返工需增加部分现场购买。',
                    '属我方供货范围内，采购漏项。' => '属我方供货范围内，采购漏项。', '属我方供货范围内，外协厂漏发货。' => '属我方供货范围内，外协厂漏发货。', '属我方供货范围内，本公司漏发货。' => '属我方供货范围内，本公司漏发货。',
                    '属我方供货范围内，需现场自行采购' => '属我方供货范围内，需现场自行采购', '现场消缺-领导决定材料当地采购。' => '现场消缺-领导决定材料当地采购。', '现场售后-公司发货不合算或来不及。' => '现场售后-公司发货不合算或来不及。'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable]) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('remark', '采购原因补充说明:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::textarea('remark', null, ['class' => 'form-control', $attr, 'rows' => 3]) !!}
            </div>
        </div>


@if (isset($paymentrequest))



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




@else









        <div id="items_excel">

        </div>

            <p class="bannerTitle">采购明细(1)</p>

            <div name="container_item">

                <div class="form-group">
                    {!! Form::label('item_name', '物品名称:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                    <div class='col-xs-8 col-sm-10'>
                        {!! Form::text('item_name', $item_name, ['class' => 'form-control', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectItemModal', 'data-name' => 'project_name', 'data-num' => '1', 'id' => 'item_name_1']) !!}
                        {!! Form::hidden('item_id', 0, ['class' => 'btn btn-sm', 'id' => 'item_id_1']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('item_spec', '规格型号:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                    <div class='col-xs-8 col-sm-10'>
                        {!! Form::text('item_spec', null, ['class' => 'form-control', 'readonly', $attr, 'id' => 'item_spec_1']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('unit', '单位:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                    <div class='col-xs-8 col-sm-10'>
                        {!! Form::text('unit', null, ['class' => 'form-control', 'readonly', $attr, 'id' => 'unit_1']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('brand', '品牌:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                    <div class='col-xs-8 col-sm-10'>
                        {!! Form::text('brand', null, ['class' => 'form-control', 'placeholder' => '', $attr, 'id' => 'size_1']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('quantity', '数量:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                    <div class='col-xs-5 col-sm-8'>
                        {!! Form::text('quantity', null, ['class' => 'form-control', 'placeholder' => '', $attr, 'id' => 'quantity_1']) !!}
                    </div>
                    <div class='col-xs-3 col-sm-2'>
                        {!! Form::select('unit_id', $unitList_hxold, null, ['class' => 'form-control', 'placeholder' => '--默认--']) !!}
                    </div>
                </div>

                {{--<div class="form-group">--}}
                    {{--{!! Form::label('material', '材质:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}--}}
                    {{--<div class='col-xs-8 col-sm-10'>--}}
                        {{--{!! Form::text('material', null, ['class' => 'form-control', 'placeholder' => '', $attr, 'id' => 'material_1']) !!}--}}
                    {{--</div>--}}
                {{--</div>--}}

                <div class="form-group">
                    {!! Form::label('unitprice', '单价:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                    <div class='col-xs-8 col-sm-10'>
                        {!! Form::text('unitprice', null, ['class' => 'form-control', 'placeholder' => '', $attr, 'id' => 'unitprice_1']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('price', '金额（元）:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                    <div class='col-xs-8 col-sm-10'>
                        {!! Form::text('price', null, ['class' => 'form-control', 'placeholder' => '', $attr, 'id' => 'price_1']) !!}
                    </div>
                </div>

            </div>




            <div id="itemMore">
            </div>
            {{--{!! Form::button('+增加明细', ['class' => 'btn btn-sm', 'id' => 'btnAddTravel']) !!}--}}
            <a href="javascript:void(0);" class="bannerTitle addMore" id="btnAddItem">+增加明细</a>





                <div id="pppaymentitemtypecontainer_1" name="pppaymentitemtypecontainer"></div>









        </div>




            <div id="itemMore">
            </div>






@endif
        {!! Form::hidden('items_string', null, ['id' => 'items_string']) !!}
        {!! Form::hidden('items_string2', null, ['id' => 'items_string2']) !!}


        <div class="form-group">
            {!! Form::label('freight', '交通或运费:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text('freight', null, ['class' => 'form-control', 'placeholder' => '请输入', $attr]) !!}
            </div>
        </div>


        <div class="form-group">
            {!! Form::label('totalprice', '合计总金额:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text('totalprice', null, ['class' => 'form-control', 'placeholder' => '会根据单价明细自动计算', $attr, 'readonly']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('paymentmethod', '支付方式:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::select('paymentmethod', array('申请人通过《请款-员工请款通用》申请采购备用金' => '申请人通过《请款-员工请款通用》申请采购备用金', '申请人使用差旅备用金先行购买，到公司凭发票报销' => '申请人使用差旅备用金先行购买，到公司凭发票报销', '申请人先行使用个人资金垫付，到公司凭发票报销' => '申请人先行使用个人资金垫付，到公司凭发票报销',
                    '申请人走《付款-对公帐户付款》由公司财务直接打款给供货单位或安装单位' => '申请人走《付款-对公帐户付款》由公司财务直接打款给供货单位或安装单位'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable]) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('invoicesituation', '发票情况:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::select('invoicesituation', array('提供13%增值税票。' => '提供13%增值税票。', '提供9%增值税票。' => '提供9%增值税票。','提供6%增值税票。' => '提供6%增值税票。','提供3%增值税票。' => '提供3%增值税票。', '提供其他税率增值税票。' => '提供其他税率增值税票。','提供普通发票。' => '提供普通发票。','吴颖浩统计专用-收据部分' => '吴颖浩统计专用-收据部分'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable]) !!}
            </div>
        </div>


    <div class="form-group">
        {!! Form::label('companyname', '公司名称:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}
        <div class='col-xs-8 col-sm-10'>
            {!! Form::text('companyname', null, ['class' => 'form-control', 'placeholder' => '拟采购公司的名称（如个人，则填个人）', $attr]) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('contact', '联系人:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}
        <div class='col-xs-8 col-sm-10'>
            {!! Form::text('contact', null, ['class' => 'form-control', 'placeholder' => '请输入拟采购公司联系人姓名', $attr]) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('phonenumber', '手机号码:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}
        <div class='col-xs-8 col-sm-10'>
            {!! Form::text('phonenumber', null, ['class' => 'form-control', 'placeholder' => '请输入拟采购联系人手机', $attr]) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('otherremark', '备注:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}
        <div class='col-xs-8 col-sm-10'>
            {!! Form::textarea('otherremark', null, ['class' => 'form-control', $attr, 'rows' => 3]) !!}
        </div>
    </div>




    <div class="form-group">
        {!! Form::label('images', '上传凭证:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}

        <div class='col-xs-8 col-sm-10'>
            <div class="row" id="previewimage">
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
                @if (Agent::isDesktop())
                    {!! Form::file('images[]', ['multiple']) !!}
                @else
                    {!! Form::button('+', ['class' => 'btn btn-sm', 'id' => 'btnSelectImage']) !!}
                    {!! Form::hidden('imagesname_mobile', null, ['class' => 'btn btn-sm']) !!}
                @endif
            @endif

        </div>
    </div>

    <div class="form-group">
        {!! Form::label('files', '附件:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}

        <div class='col-xs-8 col-sm-10'>
            @if (isset($issuedrawing))
                @foreach ($issuedrawing->drawingattachments() as $drawingattachment)
                    <a href="{!! URL($drawingattachment->path) !!}" target="_blank" id="showPaymentnode">{{ $drawingattachment->filename }}</a> <br>
                @endforeach
            @else
                {{--                {!! Form::file('files[]', ['multiple']) !!}--}}
                {!! Form::button('+', ['class' => 'btn btn-sm', 'id' => 'uploadAttach']) !!}
                <div id="lblFiles">
                </div>
                {!! Form::hidden('files_string', null, ['id' => 'files_string']) !!}
            @endif
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('associatedapprovals', '关联相关审批单:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}

        <div class='col-xs-8 col-sm-10'>
            {!! Form::button('+', ['class' => 'btn btn-sm', 'data-toggle' => 'modal', 'data-target' => '#selectApproval']) !!}
            {!! Form::hidden('associatedapprovals', null, ['class' => 'btn btn-sm']) !!}
            <div id="lblAssociatedapprovals">
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



