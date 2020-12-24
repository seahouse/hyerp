<div class="reimb">
    <div class="form-d">
        <div class="form-group">
            {!! Form::label('designdepartment', '设计部门:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::select('designdepartment', array('工艺一室' => '工艺一室', '工艺二室' => '工艺二室', '工艺三室' => '工艺三室', '系统室' => '系统室', '电控室' => '电控室'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable, 'onchange' => 'selectDesigndepartmentChange()']) !!}
            </div>
        </div>

        <!-- <div class="form-group">
            {!! Form::label('company_id', '公司:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::select('company_id', $companyList, null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable]) !!}
            </div>
        </div> -->

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
            {!! Form::label('company_name', '公司:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text('company_name', null, ['class' => 'form-control', 'readonly', $attr]) !!}
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

        @if (isset($issuedrawing))

        @else
        <div id="cabinet_detail">
            <p class="bannerTitle">柜体明细(1)</p>

            <div name="container_item">

                <div class="form-group">
                    {!! Form::label('cabinet_name', '柜体名称:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                    <div class='col-xs-8 col-sm-10'>
                        {!! Form::text('cabinet_name', null, ['class' => 'form-control', 'placeholder' => '', $attr ,'id' => 'cabinet_name_1']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('cabinet_quantity', '数量:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                    <div class='col-xs-8 col-sm-10'>
                        {!! Form::text('cabinet_quantity', null, ['class' => 'form-control', $attr, 'id' => 'cabinet_quantity_1']) !!}
                    </div>
                </div>

                {{--
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
                                    {!! Form::button('+', ['class' => 'btn btn-sm', 'id' => 'btnSelectImage', 'value' => '1', 'onclick' => 'selectImage_Mobile(1)']) !!}
                                    {!! Form::hidden('imagesname_mobile', null, ['class' => 'btn btn-sm', 'id' => 'imagesname_mobile_1']) !!}
                                @endif
                            @endif

                        </div>
                    </div>
                --}}
            </div>

            <div id="itemMore">
            </div>
            {{--{!! Form::button('+增加明细', ['class' => 'btn btn-sm', 'id' => 'btnAddTravel']) !!}--}}
            <a href="javascript:void(0);" class="bannerTitle addMore" id="btnAddItem">+增加明细</a>

            {!! Form::hidden('items_string', null, ['id' => 'items_string']) !!}
        </div>
        @endif

        {{--
        <div class="form-group">
            {!! Form::label('cabinetname', '柜体名称:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}
            <div class='col-xs-5 col-sm-8'>
                {!! Form::text('cabinetname', null, ['class' => 'form-control', 'placeholder' => '电气部填写', $attr]) !!}
            </div>
            <div class='col-xs-3 col-sm-2'>
                {!! Form::text('cabinetquantity', null, ['class' => 'form-control', 'placeholder' => '数量', $attr]) !!}
            </div>
        </div>
        --}}

        <div class="form-group">
            {!! Form::label('area', '地区:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::select('area', array('国内' => '国内', '国外' => '国外'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, 'id' => 'area_1', 'onchange' => 'selectTypeChange(this.dataset.num)', 'data-num' => '1']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('type', '类型:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::select('type', array('抛丸' => '抛丸', '油漆' => '油漆', '人工' => '人工', '铆焊' => '铆焊', '外协油漆' => '外协油漆', '板拼型钢' => '板拼型钢', '其他' => '其他'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, 'id' => 'type_1', 'onchange' => 'selectTypeChange(this.dataset.num)', 'data-num' => '1']) !!}
            </div>
        </div>

        <div id="tonnagedetailcontainer" name="tonnagedetailcontainer"></div>
        {!! Form::hidden('tonnagedetails_string', null, ['id' => 'tonnagedetails_string']) !!}

        <div class="form-group">
            {!! Form::label('sheet_thickness', '薄板漆膜厚度:（注：该厚度不含面漆）', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text('sheet_thickness', null, ['class' => 'form-control', $attr]) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('steel_thickness', '型钢和平台扶梯漆膜厚度:（注：该厚度不含面漆）', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text('steel_thickness', null, ['class' => 'form-control', $attr]) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('tonnage', '吨位（吨）:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text('tonnage', null, ['class' => 'form-control', 'placeholder' => '根据类型吨位自动计算', 'id' => 'amount', $attr, 'readonly']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('productioncompany', '制作公司:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::select('productioncompany', array('无锡生产中心' => '无锡生产中心', '无锡电气生产部' => '无锡电气生产部', '郎溪生产中心' => '郎溪生产中心',
                '宣城子公司' => '宣城子公司', '许昌子公司' => '许昌子公司', '外协单位' => '外协单位', '中易新材料' => '中易新材料', '温县生产中心' => '温县生产中心'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable, 'onchange' => 'selectProductioncompanyChange()']) !!}
            </div>
        </div>

        <div id="divOutsourcingcompany">
            <div class="form-group">
                {!! Form::label('outsourcingcompany', '外协单位:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                <div class='col-xs-8 col-sm-10'>
                    {!! Form::text('outsourcingcompany', $drawingchecker, ['class' => 'form-control', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectSupplierModal', 'data-name' => 'drawingchecker', 'data-id' => 'pohead_id', 'data-soheadid' => 'sohead_id', 'data-poheadamount' => 'pohead_amount']) !!}
                    {!! Form::hidden('outsourcingcompany_id', 0, ['class' => 'btn btn-sm', 'id' => 'outsourcingcompany_id']) !!}
                    @if (isset($reimbursement->customer_hxold->name))
                    {!! Form::hidden('customer_name2', $reimbursement->customer_hxold->name, ['class' => 'btn btn-sm', 'id' => 'customer_name2']) !!}
                    @else
                    {!! Form::hidden('customer_name2', null, ['class' => 'btn btn-sm', 'id' => 'customer_name2']) !!}
                    @endif
                </div>
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('materialsupplier', '材料供应方:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::select('materialsupplier', array('无锡华星东方' => '无锡华星东方', '河南华星东方' => '河南华星东方', '东方铁塔' => '东方铁塔', '外协单位' => '外协单位', '中易新材料' => '中易新材料'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable]) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('requestdeliverydate', '要求发货日:', ['for' => 'date', 'class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::date('requestdeliverydate', $requestdeliverydate, ['class' => 'form-control', $attr]) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('bolt', '是否栓接:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::select('bolt', array('1' => '是', '0' => '否'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable]) !!}
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

        <div class="form-group">
            {!! Form::label('associatedapprovals', '关联审批单:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}

            <div class='col-xs-8 col-sm-10'>
                {!! Form::button('+', ['class' => 'btn btn-sm', 'data-toggle' => 'modal', 'data-target' => '#selectApprovalModal']) !!}
                {!! Form::hidden('associatedapprovals', null, ['class' => 'btn btn-sm']) !!}
                <div id="lblAssociatedapprovals">
                </div>
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('syncdtdesc', '同步到钉钉组织:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::select('syncdtdesc', array('无锡' => '无锡', '许昌' => '许昌'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable]) !!}
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