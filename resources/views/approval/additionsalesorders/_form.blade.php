<div class="reimb"><div class="form-d">

        <div class="form-group">
            {!! Form::label('project_name', '项目名称:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
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
            {!! Form::label('sohead_salesmanager', '项目所属销售经理:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text('sohead_salesmanager', null, ['class' => 'form-control', 'readonly', $attr]) !!}
            </div>
        </div>

        {{--<div class="form-group">--}}
            {{--{!! Form::label('project_name', '工程名称:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}--}}
            {{--<div class='col-xs-8 col-sm-10'>--}}
                {{--@if (isset($projectsitepurchase->sohead_hxold->descrip))--}}
                    {{--{!! Form::text('project_name', $projectsitepurchase->sohead_hxold->descrip, ['class' => 'form-control', $attr]) !!}--}}
                {{--@else--}}
                    {{--{!! Form::text('project_name', $project_name, ['class' => 'form-control', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectProjectModal', 'data-name' => 'project_name_1', 'data-id' => 'sohead_id_1', 'data-num' => '1', 'id' => 'project_name_1']) !!}--}}
                    {{--{!! Form::hidden('sohead_id', 0, ['class' => 'btn btn-sm', 'id' => 'sohead_id_1']) !!}--}}
                {{--@endif--}}
            {{--</div>--}}
        {{--</div>--}}

        {{--<div class="form-group">--}}
            {{--{!! Form::label('sohead_number', '项目订单编号:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}--}}
            {{--<div class='col-xs-8 col-sm-10'>--}}
                {{--@if (isset($projectsitepurchase->sohead_hxold->number))--}}
                    {{--{!! Form::text('sohead_number', $projectsitepurchase->sohead_hxold->number, ['class' => 'form-control', 'readonly', $attr, 'id' => 'sohead_number_1']) !!}--}}
                {{--@else--}}
                    {{--{!! Form::text('sohead_number', null, ['class' => 'form-control', 'readonly', $attr, 'id' => 'sohead_number_1']) !!}--}}
                {{--@endif--}}
            {{--</div>--}}
        {{--</div>--}}

        {{--<div class="form-group">--}}
            {{--{!! Form::label('sohead_salesmanager', '订单所属销售经理:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}--}}
            {{--<div class='col-xs-8 col-sm-10'>--}}
                {{--@if (isset($projectsitepurchase->sohead_hxold->salesmanager))--}}
                    {{--{!! Form::text('sohead_salesmanager', $projectsitepurchase->sohead_hxold->salesmanager, ['class' => 'form-control', 'readonly', $attr]) !!}--}}
                {{--@else--}}
                    {{--{!! Form::text('sohead_salesmanager', null, ['class' => 'form-control', 'readonly', $attr]) !!}--}}
                {{--@endif--}}
            {{--</div>--}}
        {{--</div>--}}

        <div class="form-group">
            {!! Form::label('signcontract_condition', '已签增补合同？:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::select('signcontract_condition', array('甲方与我们已签定增补合同' => '甲方与我们已签定增补合同', '甲方与我们已签增补单，合同还未签定。' => '甲方与我们已签增补单，合同还未签定。'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable]) !!}
            </div>
        </div>



        <div class="form-group">
            {!! Form::label('reason', '本项签增原因详细说明:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::textarea('reason', null, ['class' => 'form-control', $attr, 'rows' => 3]) !!}
            </div>
        </div>

        <p class="bannerTitle">增补内容明细(1)</p>

        <div name="container_item">
            <div class="form-group">
                {!! Form::label('type', '增补内容:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                <div class='col-xs-8 col-sm-10'>
                    {!! Form::select('type', array('机务材料' => '机务材料', '机务设备' => '机务设备', '电气材料' => '电气材料', '电气设备' => '电气设备', '人工用量' => '人工用量', '运费' => '运费', '其他类别' => '其他类别'), null, ['class' => 'form-control', $attr, 'id' => 'type_1']) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('otherremark', '其他类别补充说明:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                <div class='col-xs-8 col-sm-10'>
                    {!! Form::text('otherremark', null, ['class' => 'form-control', $attr, 'id' => 'otherremark_1']) !!}
                </div>
            </div>


            <div class="form-group">
                {!! Form::label('unit', '单位:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                <div class='col-xs-8 col-sm-10'>
                    {!! Form::text('unit', null, ['class' => 'form-control', $attr, 'id' => 'unit_1']) !!}
                </div>
            </div>



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
                {!! Form::label('amount', '此项增补金额（元）:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                <div class='col-xs-8 col-sm-10'>
                    {!! Form::text('amount', null, ['class' => 'form-control', 'placeholder' => '', $attr, 'id' => 'amount_1']) !!}
                </div>
            </div>
        </div>


        <div id="itemMore">
        </div>
        {{--{!! Form::button('+增加明细', ['class' => 'btn btn-sm', 'id' => 'btnAddTravel']) !!}--}}
        <a href="javascript:void(0);" class="bannerTitle addMore" id="btnAddItem">+增加明细</a>

        {!! Form::hidden('items_string', null, ['id' => 'items_string']) !!}

        <div class="form-group">
            {!! Form::label('remark', '备注:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text('remark', null, ['class' => 'form-control', 'placeholder' => '请输入', $attr]) !!}
            </div>
        </div>




        {{--<div class="form-group">--}}
            {{--{!! Form::label('supplier_name', '支付对象:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}--}}
            {{--<div class='col-xs-8 col-sm-10'>--}}
                {{--{!! Form::text('supplier_name', null, ['class' => 'form-control', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectSupplierModal', 'data-name' => 'supplier_name', 'data-id' => 'supplier_id']) !!}--}}
                {{--{!! Form::hidden('supplier_id', 0, ['class' => 'btn btn-sm', 'id' => 'supplier_id']) !!}--}}
            {{--</div>--}}
        {{--</div>--}}


        {{--<div class="form-group">--}}
            {{--{!! Form::label('supplier_bank', '开户行:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}--}}
            {{--<div class='col-xs-8 col-sm-10'>--}}

                {{--{!! Form::text('supplier_bank', null, ['class' => 'form-control', 'placeholder' => '点击选择', 'readonly', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectSupplierBankModal', 'data-name' => 'supplier_name', 'data-id' => 'supplier_id']) !!}--}}

                {{--{!! Form::hidden('vendbank_id', 0, ['id' => 'vendbank_id']) !!}--}}
            {{--</div>--}}
        {{--</div>--}}


        {{--<div class="form-group">--}}
            {{--{!! Form::label('supplier_bankaccountnumber', '银行账号:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}--}}
            {{--<div class='col-xs-8 col-sm-10'>--}}
                {{--{!! Form::text('supplier_bankaccountnumber', null, ['class' => 'form-control', 'readonly', $attr]) !!}--}}
            {{--</div>--}}
        {{--</div>--}}

        <div class="form-group">
            {!! Form::label('files', '签增单上传（到钉钉审批）:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}

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

        {{--<div class="form-group">--}}
            {{--{!! Form::label('files', '签增单上传:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}--}}

            {{--<div class='col-xs-8 col-sm-10'>--}}
                {{--@if (isset($issuedrawing))--}}
                    {{--@foreach ($issuedrawing->drawingattachments() as $drawingattachment)--}}
                        {{--<a href="{!! URL($drawingattachment->path) !!}" target="_blank" id="showPaymentnode">{{ $drawingattachment->filename }}</a> <br>--}}
                    {{--@endforeach--}}
                {{--@else--}}
                                    {{--{!! Form::file('files[]', ['multiple']) !!}--}}
                    {{--{!! Form::button('+', ['class' => 'btn btn-sm', 'id' => 'uploadAttach']) !!}--}}
                    {{--<div id="lblFiles">--}}
                    {{--</div>--}}
                    {{--{!! Form::hidden('files_string', null, ['id' => 'files_string']) !!}--}}
                {{--@endif--}}
            {{--</div>--}}
        {{--</div>--}}

        {{--<div class="form-group" id="divAssociatedapprovals">--}}
            {{--{!! Form::label('associated_approval_projectpurchase', '关联《工程采购》审批单:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}--}}
            {{--<div class='col-xs-8 col-sm-10'>--}}
                {{--@if (isset($projectsitepurchase))--}}
                {{--@else--}}
                    {{--{!! Form::button('+', ['class' => 'btn btn-sm', 'data-toggle' => 'modal', 'data-target' => '#selectApproval']) !!}--}}
                    {{--{!! Form::hidden('associated_approval_projectpurchase', null, ['class' => 'btn btn-sm']) !!}--}}
                    {{--<div id="lblAssociatedapprovals">--}}
                    {{--</div>--}}
                {{--@endif--}}
            {{--</div>--}}
        {{--</div>--}}

        <div class="form-group">
            {!! Form::label('images', '增补合同上传:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}

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
                    @endif
                @endif

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



