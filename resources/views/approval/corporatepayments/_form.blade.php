<div class="reimb"><div class="form-d">

        <div class="form-group">
            {!! Form::label('suppliertype', '供应商类型:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::select('suppliertype', array('安装公司' => '安装公司', '机务设备类' => '机务设备类', '电气设备类' => '电气设备类', '安装材料类' => '安装材料类', '代理或服务类' => '代理或服务类', '厂部常用类' => '厂部常用类', '其他' => '其他'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable]) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('paymenttype', '付款类型:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::select('paymenttype', array('预付款' => '预付款', '进度款' => '进度款', '到货款' => '到货款', '安装结束款' => '安装结束款', '调试运行款' => '调试运行款', '环保验收款' => '环保验收款', '质保金' => '质保金'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable]) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('position', '职位:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                @if (isset($projectsitepurchase->vendordeduction_descrip))
                    {!! Form::select('position', array('部门经理（含）以上职位' => '部门经理（含）以上职位', '部门经理（含）以下职位' => '部门经理（含）以下职位'), $projectsitepurchase->vendordeduction_descrip, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable]) !!}
                @else
                    {!! Form::select('position', array('部门经理（含）以上职位' => '部门经理（含）以上职位', '部门经理（含）以下职位' => '部门经理（含）以下职位'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable]) !!}
                @endif
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('amounttype', '费用类型:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::select('amounttype', array('工程现场采购费用相关' => '工程现场采购费用相关', '安装合同安装费付款' => '安装合同安装费付款'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', 'onchange' => 'selectAmounttypeChange()', $attr, $attrdisable]) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('project_name', '项目名称:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                @if (isset($projectsitepurchase->sohead_hxold->descrip))
                    {!! Form::text('project_name', $projectsitepurchase->sohead_hxold->descrip, ['class' => 'form-control', $attr]) !!}
                @else
                    {!! Form::text('project_name', $project_name, ['class' => 'form-control', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectProjectModal', 'data-name' => 'project_name_1', 'data-id' => 'sohead_id', 'data-num' => '1', 'id' => 'project_name_1']) !!}
                    {!! Form::hidden('sohead_id', 0, ['class' => 'btn btn-sm', 'id' => 'sohead_id']) !!}
                @endif
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('sohead_number', '项目编号:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                @if (isset($projectsitepurchase->sohead_hxold->number))
                    {!! Form::text('sohead_number', $projectsitepurchase->sohead_hxold->number, ['class' => 'form-control', 'readonly', $attr, 'id' => 'sohead_number_1']) !!}
                @else
                    {!! Form::text('sohead_number', null, ['class' => 'form-control', 'readonly', $attr, 'id' => 'sohead_number_1']) !!}
                @endif
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('sohead_salesmanager', '项目属于销售员:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                @if (isset($projectsitepurchase->sohead_hxold->salesmanager))
                    {!! Form::text('sohead_salesmanager', $projectsitepurchase->sohead_hxold->salesmanager, ['class' => 'form-control', 'readonly', $attr]) !!}
                @else
                    {!! Form::text('sohead_salesmanager', null, ['class' => 'form-control', 'readonly', $attr]) !!}
                @endif
            </div>
        </div>

        {{--<div class="form-group">--}}
            {{--{!! Form::label('projecttype', '项目类型:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}--}}
            {{--<div class='col-xs-8 col-sm-10'>--}}
                {{--@if (isset($projectsitepurchase->sohead_hxold))--}}
                    {{--{!! Form::text('projecttype', $projectsitepurchase->sohead_hxold->C == 0 ? 'EP项目' : 'EPC项目', ['class' => 'form-control', 'readonly', $attr]) !!}--}}
                {{--@else--}}
                    {{--{!! Form::text('projecttype', null, ['class' => 'form-control', 'readonly', $attr]) !!}--}}
                {{--@endif--}}
            {{--</div>--}}
        {{--</div>--}}




        {{--<div class="form-group">--}}
            {{--{!! Form::label('outsourcingcompany', '外协设备商全称:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}--}}
            {{--<div class='col-xs-8 col-sm-10'>--}}
                {{--{!! Form::text('outsourcingcompany', null, ['class' => 'form-control', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectSupplierModal', 'data-name' => 'drawingchecker', 'data-id' => 'pohead_id', 'data-soheadid' => 'sohead_id', 'data-poheadamount' => 'pohead_amount']) !!}--}}
                {{--{!! Form::hidden('outsourcingcompany_id', 0, ['class' => 'btn btn-sm', 'id' => 'outsourcingcompany_id']) !!}--}}
                {{--@if (isset($reimbursement->customer_hxold->name))--}}
                    {{--{!! Form::hidden('customer_name2', $reimbursement->customer_hxold->name, ['class' => 'btn btn-sm', 'id' => 'customer_name2']) !!}--}}
                {{--@else--}}
                    {{--{!! Form::hidden('customer_name2', null, ['class' => 'btn btn-sm', 'id' => 'customer_name2']) !!}--}}
                {{--@endif--}}
            {{--</div>--}}
        {{--</div>--}}

        <div id="divPohead_number">
            <div class="form-group">
                {!! Form::label('pohead_number', '外协合同编号:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                <div class='col-xs-8 col-sm-10'>
                    @if (isset($projectsitepurchase->sohead_hxold->descrip))
                        {!! Form::text('pohead_number', $projectsitepurchase->sohead_hxold->descrip, ['class' => 'form-control', $attr]) !!}
                    @else
                        {!! Form::text('pohead_number', $project_name, ['class' => 'form-control', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectPoheadModal', 'data-name' => 'project_name_1']) !!}
                        {!! Form::hidden('pohead_id', 0, ['class' => 'btn btn-sm', 'id' => 'pohead_id']) !!}
                    @endif
                </div>
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('remark', '付款说明:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::textarea('remark', null, ['class' => 'form-control', $attr, 'rows' => 3]) !!}
            </div>
        </div>

        <div id="divAmountpercent">
            <div class="form-group">
                {!! Form::label('amountpercent', '付款比例（%）:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}
                <div class='col-xs-8 col-sm-10'>
                    {!! Form::text('amountpercent', null, ['class' => 'form-control', 'placeholder' => '请输入', $attr]) !!}
                </div>
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('amount', '付款总额（元）:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text('amount', null, ['class' => 'form-control', 'placeholder' => '请输入', $attr]) !!}
            </div>
        </div>


        <div class="form-group">
            {!! Form::label('paymentmethod', '付款方式:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {{-- 支付方式需要与钉钉审批一致，钉钉审批需要与ERP中的付款审批一致 --}}
                {!! Form::select('paymentmethod', array('支票' => '支票', '贷记' => '贷记', '电汇' => '电汇', '汇票' => '汇票', '现金' => '现金', '银行卡' => '银行卡', '其他' => '其他'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable]) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('paydate', '付款日期:', ['for' => 'date', 'class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::date('paydate', $paydate, ['class' => 'form-control', $attr]) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('supplier_name', '支付对象:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text('supplier_name', null, ['class' => 'form-control', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectSupplierModal', 'data-name' => 'supplier_name', 'data-id' => 'supplier_id']) !!}
                {!! Form::hidden('supplier_id', 0, ['class' => 'btn btn-sm', 'id' => 'supplier_id']) !!}
            </div>
        </div>


        <div class="form-group">
            {!! Form::label('supplier_bank', '开户行:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>

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

        <div class="form-group" id="divAssociatedapprovals">
            {!! Form::label('associated_approval_projectpurchase', '关联《工程采购》审批单:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                @if (isset($projectsitepurchase))
                @else
                    {!! Form::button('+', ['class' => 'btn btn-sm', 'data-toggle' => 'modal', 'data-target' => '#selectApproval']) !!}
                    {!! Form::hidden('associated_approval_projectpurchase', null, ['class' => 'btn btn-sm']) !!}
                    <div id="lblAssociatedapprovals">
                    </div>
                @endif
            </div>
        </div>



    {{--<div class="form-group">--}}
        {{--{!! Form::label('images', '上传购买凭证:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}--}}

        {{--<div class='col-xs-8 col-sm-10'>--}}
            {{--<div class="row" id="previewimage">--}}
            {{--</div>--}}
            {{--@if (isset($projectsitepurchase))--}}
                {{--<div class="row" id="previewimage2">--}}
                    {{--@foreach ($projectsitepurchase->projectsitepurchaseattachments() as $projectsitepurchaseattachment)--}}
                        {{--<div class="col-xs-6 col-md-3">--}}
                            {{--<div class="thumbnail">--}}
                                {{--<img src="{!! $projectsitepurchaseattachment->path !!}" />--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--@endforeach--}}
                {{--</div>--}}
            {{--@else--}}
                {{--@if (Agent::isDesktop())--}}
                    {{--{!! Form::file('images[]', ['multiple']) !!}--}}
                {{--@else--}}
                    {{--{!! Form::button('+', ['class' => 'btn btn-sm', 'id' => 'btnSelectImage']) !!}--}}
                    {{--{!! Form::hidden('imagesname_mobile', null, ['class' => 'btn btn-sm']) !!}--}}
                {{--@endif--}}
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



