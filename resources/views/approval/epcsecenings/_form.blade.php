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
                {!! Form::label('supplier_name', '安装公司全称:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                <div class='col-xs-8 col-sm-10'>
                    {!! Form::text('supplier_name', null, ['class' => 'form-control', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectSupplierModal', 'data-name' => 'supplier_name', 'data-id' => 'supplier_id']) !!}
                    {!! Form::hidden('supplier_id', 0, ['class' => 'btn btn-sm', 'id' => 'supplier_id']) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('pohead_number', '安装队安装合同ERP编号:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                <div class='col-xs-8 col-sm-10'>
                    @if (isset($projectsitepurchase->sohead_hxold->descrip))
                        {!! Form::text('pohead_number', $projectsitepurchase->sohead_hxold->descrip, ['class' => 'form-control', $attr]) !!}
                    @else
                        {!! Form::text('pohead_number', null, ['class' => 'form-control', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectPoheadModal', 'data-name' => 'project_name_1']) !!}
                        {!! Form::hidden('pohead_id', 0, ['class' => 'btn btn-sm', 'id' => 'pohead_id']) !!}
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
    {{--{!! Form::label('drawingchecker', '图纸校核人:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}--}}
    {{--<div class='col-xs-8 col-sm-10'>--}}
    {{--{!! Form::text('drawingchecker', $drawingchecker, ['class' => 'form-control', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectDrawingcheckerModal', 'data-name' => 'drawingchecker', 'data-id' => 'pohead_id', 'data-soheadid' => 'sohead_id', 'data-poheadamount' => 'pohead_amount']) !!}--}}
    {{--{!! Form::hidden('drawingchecker_id', 0, ['class' => 'btn btn-sm', 'id' => 'drawingchecker_id']) !!}--}}
    {{--@if (isset($reimbursement->customer_hxold->name))--}}
        {{--{!! Form::hidden('customer_name2', $reimbursement->customer_hxold->name, ['class' => 'btn btn-sm', 'id' => 'customer_name2']) !!}--}}
    {{--@else--}}
        {{--{!! Form::hidden('customer_name2', null, ['class' => 'btn btn-sm', 'id' => 'customer_name2']) !!}--}}
    {{--@endif--}}
    {{--</div>--}}
{{--</div>--}}

        <div class="form-group">
            {!! Form::label('additional_design_department', '增补项所属设计部门:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::select('additional_design_department', array('工艺一室' => '工艺一室', '工艺二室' => '工艺二室', '工艺三室' => '工艺三室', '系统室' => '系统室', '电控室' => '电控室',
                    '不涉及设计部门' => '不涉及设计部门'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable, 'onchange' => 'selectDesigndepartmentChange()']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('additional_source', '增补项所属来源:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::select('additional_source', array('由供应商所供外购件' => '由供应商所供外购件', '由外协厂加工钢结构件' => '由外协厂加工钢结构件', '由公司-无锡生产中心生产' => '由公司-无锡生产中心生产', '由公司-电气生产部生产' => '由公司-电气生产部生产', '由公司-宣城生产中心生产' => '由公司-宣城生产中心生产',
                    '由公司-许昌生产中心生产' => '由公司-许昌生产中心生产', '不涉及到生产中心部分' => '不涉及到生产中心部分'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable, 'onchange' => 'selectDesigndepartmentChange()']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('additional_source_department', '造成增补的责任归集部门:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::select('additional_source_department', array('工艺一室' => '工艺一室', '工艺二室' => '工艺二室', '工艺三室' => '工艺三室', '系统室' => '系统室', '电控室' => '电控室',
                    '无锡生产中心' => '无锡生产中心', '宣城生产中心' => '宣城生产中心', '许昌生产中心' => '许昌生产中心', '电气生产部' => '电气生产部', '采购部' => '采购部',
                    '销售部' => '销售部', '外协厂-钢结构加工' => '外协厂-钢结构加工', '供应商-设备供应类' => '供应商-设备供应类', '供应商-材料供应类' => '供应商-材料供应类', '其他原因' => '其他原因'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable, 'onchange' => 'selectDesigndepartmentChange()']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('additional_reason', '增补原因:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::select('additional_reason', array('短缺增补' => '短缺增补', '图纸差异增补' => '图纸差异增补', '范围外增补' => '范围外增补', '业主额外增补' => '业主额外增补', '业主合理增补' => '业主合理增补',
                    '配合增补' => '配合增补'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable, 'onchange' => 'selectAdditionalReasonChange()']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('need_issuedrawing', '需要技术部门出图？:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::select('need_issuedrawing', array('技术部出图并出具工作量清单' => '技术部出图并出具工作量清单', '技术部没有出图' => '技术部没有出图', '技术部没有出图但出具工作量清单' => '技术部没有出图但出具工作量清单', '技术部没有出图也没有出工作量清单' => '技术部没有出图也没有出工作量清单'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable, 'onchange' => 'selectDesigndepartmentChange()']) !!}
            </div>
        </div>

        <div id="div_design_change_sheet">
            <div class="form-group">
                {!! Form::label('design_change_sheet', '是否有设计变更单:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                <div class='col-xs-8 col-sm-10'>
                    {!! Form::select('design_change_sheet', array('技术部下发了设计变更单' => '技术部下发了设计变更单', '技术部没有下发设计变更单' => '技术部没有下发设计变更单', '增补工作不需要设计变更单' => '增补工作不需要设计变更单'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable, 'onchange' => 'selectDesignChangeSheetChange()']) !!}
                </div>
            </div>
        </div>

        <div id="div_short_additional_reason">
            <div class="form-group">
                {!! Form::label('short_additional_reason', '短缺增补-补充原因:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                <div class='col-xs-8 col-sm-10'>
                    {!! Form::select('short_additional_reason', array('该部分内容不在安装队的工作量清单中' => '该部分内容不在安装队的工作量清单中', '该部分内容由于我方工作量清单计算短缺导致' => '该部分内容由于我方工作量清单计算短缺导致', '该部分内容由于我方工作量清单计算错误导致' => '该部分内容由于我方工作量清单计算错误导致', '该部分内容属安装合同变更增加工作量' => '该部分内容属安装合同变更增加工作量'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable, 'onchange' => 'selectDesigndepartmentChange()']) !!}
                </div>
            </div>
        </div>

        <div id="div_drawing_additional_reason">
            <div class="form-group">
                {!! Form::label('drawing_additional_reason', '图纸差异增补-补充原因:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                <div class='col-xs-8 col-sm-10'>
                    {!! Form::select('drawing_additional_reason', array('施工队按我方图纸施工安装，但不符合甲方或监理要求，需返工或修正' => '施工队按我方图纸施工安装，但不符合甲方或监理要求，需返工或修正', '公司技术部设计并提供的图纸与现场环境不匹配，需返工修正' => '公司技术部设计并提供的图纸与现场环境不匹配，需返工修正'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable, 'onchange' => 'selectDesigndepartmentChange()']) !!}
                </div>
            </div>
        </div>

        <div id="div_extra_additional_reason">
            <div class="form-group">
                {!! Form::label('extra_additional_reason', '范围外增补-补充原因:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                <div class='col-xs-8 col-sm-10'>
                    {!! Form::select('extra_additional_reason', array('由公司生产中心生产，但现场无法安装，需要安装队现场修正' => '由公司生产中心生产，但现场无法安装，需要安装队现场修正', '由公司生产中心生产，发货有漏件，漏件部分由现场加工制作' => '由公司生产中心生产，发货有漏件，漏件部分由现场加工制作', '外协加工厂加工结构件有缺陷，需现场修正' => '外协加工厂加工结构件有缺陷，需现场修正', '外协加工厂加工结构件漏件，需现场加工制作' => '外协加工厂加工结构件漏件，需现场加工制作', '外协加工厂加工结构件散件发货，需现场额外加工造成增补' => '外协加工厂加工结构件散件发货，需现场额外加工造成增补',
                        '外协加工厂加工结构件发货运输造成损坏，需现场修复' => '外协加工厂加工结构件发货运输造成损坏，需现场修复', '供应商提供的设备不能满足现场要求或无法安装，需现场修正' => '供应商提供的设备不能满足现场要求或无法安装，需现场修正', '供应商提供的设备中漏发配件，需现场加工制作' => '供应商提供的设备中漏发配件，需现场加工制作', '供应商提供的设备运输损坏，需要现场修正' => '供应商提供的设备运输损坏，需要现场修正', '现场前置工序进度缓慢或暂停，导致安装队多次进场、停工导致出现大面积窝工' => '现场前置工序进度缓慢或暂停，导致安装队多次进场、停工导致出现大面积窝工'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable, 'onchange' => 'selectExtraAdditionalReasonChange()']) !!}
                </div>
            </div>
        </div>

        <div id="div_owner_additional_reason">
            <div class="form-group">
                {!! Form::label('owner_additional_reason', '业主额外增补-补充原因:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                <div class='col-xs-8 col-sm-10'>
                    {!! Form::select('owner_additional_reason', array('该部分内容不在我方的供货范围，甲方强行要求我方做掉' => '该部分内容不在我方的供货范围，甲方强行要求我方做掉'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable, 'onchange' => 'selectDesigndepartmentChange()']) !!}
                </div>
            </div>
        </div>

        <div id="div_owner_additional_reasonalreason">
            <div class="form-group">
                {!! Form::label('owner_additional_reasonalreason', '业主合理增补-补充原因:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                <div class='col-xs-8 col-sm-10'>
                    {!! Form::select('owner_additional_reasonalreason', array('该部分内容不在我方的供货范围，属于保障装置安全平稳运行的合理诉求' => '该部分内容不在我方的供货范围，属于保障装置安全平稳运行的合理诉求'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable, 'onchange' => 'selectDesigndepartmentChange()']) !!}
                </div>
            </div>
        </div>

        <div id="div_coordinate_additional_reason">
            <div class="form-group">
                {!! Form::label('coordinate_additional_reason', '配合增补-补充原因:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                <div class='col-xs-8 col-sm-10'>
                    {!! Form::select('coordinate_additional_reason', array('现场调试过程中不因安装队安装施工原因造成的工作量' => '现场调试过程中不因安装队安装施工原因造成的工作量', '供应商提供的设备不能满足现场运行，调试或168中拆卸安装修复所产生的工作量' => '供应商提供的设备不能满足现场运行，调试或168中拆卸安装修复所产生的工作量', '因供应商提供设备或材料质量问题造成售后，从而产生安装队的工作量' => '因供应商提供设备或材料质量问题造成售后，从而产生安装队的工作量', '售后服务过程中，除去设备或材料质量原因、不在安装队合同义务内所产生的工作量' => '售后服务过程中，除去设备或材料质量原因、不在安装队合同义务内所产生的工作量'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable, 'onchange' => 'selectCoordinateAdditionalReasonChange()']) !!}
                </div>
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('additional_reason_detaildesc', '增补原因详细说明:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::textarea('additional_reason_detaildesc', null, ['class' => 'form-control', $attr, 'rows' => 3]) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('additional_content', '增补内容包含:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::select('additional_content', array('仅材料费用' => '仅材料费用', '材料费用+人工费用' => '材料费用+人工费用', '材料费用+吊机费用' => '材料费用+吊机费用', '材料费用+人工费用+吊机费用' => '材料费用+人工费用+吊机费用'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable, 'onchange' => 'selectAdditionalContentChange()']) !!}
            </div>
        </div>


        <div id="material_detail">
            <p class="bannerTitle">增补所用材料部分（明细<15项）(<span class="moreOrder">1</span>)</p>

            <div name="container_item">

                <div class="form-group">
                    {!! Form::label('material_type', '材料类别:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                    <div class='col-xs-8 col-sm-10'>
                        {!! Form::select('material_type', array('不锈钢管材' => '不锈钢管材', '不锈钢板材' => '不锈钢板材', '钢材型材' => '钢材型材', '钢材板材' => '钢材板材', '钢材管材' => '钢材管材',
                            '保温材料' => '保温材料', '电气材料' => '电气材料', '安装消耗材料' => '安装消耗材料', '管材配件' => '管材配件', '防腐材料' => '防腐材料',
                            '劳保用品' => '劳保用品', '施工期间甲方收取的费用' => '施工期间甲方收取的费用', '其他类别' => '其他类别'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable]) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('item_name', '物品名称:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                    <div class='col-xs-8 col-sm-10'>
                        {!! Form::text('item_name', null, ['class' => 'form-control', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectItemModal', 'data-name' => 'project_name', 'data-num' => '1', 'id' => 'item_name_1']) !!}
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
                    {!! Form::label('unit', '计价单位:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                    <div class='col-xs-8 col-sm-10'>
                        {!! Form::text('unit', null, ['class' => 'form-control', 'readonly', $attr, 'id' => 'unit_1']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('quantity', '数量:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                    <div class='col-xs-8 col-sm-10'>
                        {!! Form::text('quantity', null, ['class' => 'form-control', $attr, 'id' => 'quantity_1']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('unitprice', '单价（元）:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                    <div class='col-xs-8 col-sm-10'>
                        {!! Form::text('unitprice', null, ['class' => 'form-control', $attr, 'id' => 'unitprice_1']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('remark', '备注:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}
                    <div class='col-xs-8 col-sm-10'>
                        {!! Form::textarea('remark', null, ['class' => 'form-control', $attr, 'rows' => 3]) !!}
                    </div>
                </div>
            </div>



            <div class="moreDiv" id="itemMore">
            </div>
            {{--{!! Form::button('+增加明细', ['class' => 'btn btn-sm', 'id' => 'btnAddTravel']) !!}--}}
            <a href="javascript:void(0);" class="bannerTitle addMore" id="btnAddItem">+增加明细</a>

            {!! Form::hidden('items_string', null, ['id' => 'items_string']) !!}
        </div>

        <div id="humanday_detail">
            <p class="bannerTitle">增补所用人工部分（明细不大于2项）(<span class="moreOrder">1</span>)</p>

            <div name="container_item">

                <div class="form-group">
                    {!! Form::label('humandays_type', '人工类型:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                    <div class='col-xs-8 col-sm-10'>
                        {!! Form::text('humandays_type', null, ['class' => 'form-control', $attr, 'id' => 'humandays_type_1']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('humandays', '人工数:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                    <div class='col-xs-8 col-sm-10'>
                        {!! Form::text('humandays', null, ['class' => 'form-control', $attr, 'id' => 'humandays_1']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('humandays_unitprice', '人工单价（元）:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                    <div class='col-xs-8 col-sm-10'>
                        {!! Form::text('humandays_unitprice', null, ['class' => 'form-control', $attr, 'id' => 'humandays_unitprice_1']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('remark', '备注:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}
                    <div class='col-xs-8 col-sm-10'>
                        {!! Form::textarea('remark', null, ['class' => 'form-control', $attr, 'rows' => 3]) !!}
                    </div>
                </div>
            </div>



            <div class="moreDiv" id="itemMore_humanday">
            </div>
            {{--{!! Form::button('+增加明细', ['class' => 'btn btn-sm', 'id' => 'btnAddTravel']) !!}--}}
            <a href="javascript:void(0);" class="bannerTitle addMore" id="btnAddItemHumanday">+增加明细</a>

            {!! Form::hidden('items_string_humanday', null, ['id' => 'items_string_humanday']) !!}
        </div>

        <div id="crane_detail">
            <p class="bannerTitle">增补所用吊机台班（明细不大于2项）(<span class="moreOrder">1</span>)</p>

            <div name="container_item">

                <div class="form-group">
                    {!! Form::label('crane_type', '吊机型号:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                    <div class='col-xs-8 col-sm-10'>
                        {!! Form::text('crane_type', null, ['class' => 'form-control', $attr, 'id' => 'crane_type_1']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('number', '台数班:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                    <div class='col-xs-8 col-sm-10'>
                        {!! Form::text('number', null, ['class' => 'form-control', $attr, 'id' => 'number_1']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('unitprice', '台班单价（元）:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                    <div class='col-xs-8 col-sm-10'>
                        {!! Form::text('unitprice', null, ['class' => 'form-control', $attr, 'id' => 'unitprice_1']) !!}
                    </div>
                </div>

            </div>



            <div class="moreDiv" id="itemMore_crane">
            </div>
            {{--{!! Form::button('+增加明细', ['class' => 'btn btn-sm', 'id' => 'btnAddTravel']) !!}--}}
            <a href="javascript:void(0);" class="bannerTitle addMore" id="btnAddItemCrane">+增加明细</a>

            {!! Form::hidden('items_string_crane', null, ['id' => 'items_string_crane']) !!}
        </div>







<div class="form-group">
    {!! Form::label('bothsigned', '双方签字的安装队工作量表:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}

    <div class='col-xs-8 col-sm-10'>
        @if (isset($issuedrawing))
            @foreach ($issuedrawing->drawingattachments() as $drawingattachment)
                 <a href="{!! URL($drawingattachment->path) !!}" target="_blank" id="showPaymentnode">{{ $drawingattachment->filename }}</a> <br>
            @endforeach
        @else
            @if (!str_contains(Agent::getUserAgent(), 'DingTalk'))
                {!! Form::file('bothsigned[]', ['multiple']) !!}
            @else
                {!! Form::button('+', ['class' => 'btn btn-sm', 'id' => 'uploadAttach_bothsigned']) !!}
                <div id="lblFiles_bothsigned">
                </div>
                {!! Form::hidden('files_string_bothsigned', '', ['id' => 'files_string_bothsigned']) !!}
            @endif
        @endif
    </div>
</div>

        <div id="div_huaxingworksheet">
            <div class="form-group">
                {!! Form::label('huaxingworksheet', '华星东方下发的工作联系单:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}

                <div class='col-xs-8 col-sm-10'>
                    @if (isset($issuedrawing))
                        @foreach ($issuedrawing->drawingattachments() as $drawingattachment)
                            <a href="{!! URL($drawingattachment->path) !!}" target="_blank" id="showPaymentnode">{{ $drawingattachment->filename }}</a> <br>
                        @endforeach
                    @else
                        @if (!str_contains(Agent::getUserAgent(), 'DingTalk'))
                            {!! Form::file('huaxingworksheet[]', ['multiple']) !!}
                        @else
                            {!! Form::button('+', ['class' => 'btn btn-sm', 'id' => 'uploadAttach_huaxingworksheet']) !!}
                            <div id="lblFiles_huaxingworksheet">
                            </div>
                            {!! Form::hidden('files_string_huaxingworksheet', '', ['id' => 'files_string_huaxingworksheet']) !!}
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <div id="div_installworksheet">
            <div class="form-group">
                {!! Form::label('installworksheet', '安装队下发的工作联系单:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}

                <div class='col-xs-8 col-sm-10'>
                    @if (isset($issuedrawing))
                        @foreach ($issuedrawing->drawingattachments() as $drawingattachment)
                            <a href="{!! URL($drawingattachment->path) !!}" target="_blank" id="showPaymentnode">{{ $drawingattachment->filename }}</a> <br>
                        @endforeach
                    @else
                        @if (!str_contains(Agent::getUserAgent(), 'DingTalk'))
                            {!! Form::file('installworksheet[]', ['multiple']) !!}
                        @else
                            {!! Form::button('+', ['class' => 'btn btn-sm', 'id' => 'uploadAttach_installworksheet']) !!}
                            <div id="lblFiles_installworksheet">
                            </div>
                            {!! Form::hidden('files_string_installworksheet', '', ['id' => 'files_string_installworksheet']) !!}
                        @endif
                    @endif
                </div>
            </div>
        </div>

<div class="form-group">
    {!! Form::label('beforeimage', '增补之前图片:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
    
    <div class='col-xs-8 col-sm-10'>
        <div class="row" id="previewimage_beforeimage">
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
            @if (!str_contains(Agent::getUserAgent(), 'DingTalk'))
                {!! Form::file('beforeimage[]', ['multiple']) !!}
            @else
                {!! Form::button('+', ['class' => 'btn btn-sm', 'id' => 'uploadAttach_beforeimage']) !!}
                <div id="lblFiles_beforeimage">
                </div>
                {!! Form::hidden('files_string_beforeimage', '', ['id' => 'files_string_beforeimage']) !!}
            @endif
            {{--@if (Agent::isDesktop())--}}
                {{--{!! Form::file('beforeimage[]', ['multiple']) !!}--}}
            {{--@else--}}
                {{--{!! Form::button('+', ['class' => 'btn btn-sm', 'id' => 'btnSelectImage_beforeimage']) !!}--}}
            {{--@endif            --}}
        @endif

    </div>
</div>

        <div class="form-group">
            {!! Form::label('afterimage', '增补施工后图片:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}

            <div class='col-xs-8 col-sm-10'>
                <div class="row" id="previewimage_afterimage">
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
                    @if (!str_contains(Agent::getUserAgent(), 'DingTalk'))
                        {!! Form::file('afterimage[]', ['multiple']) !!}
                    @else
                        {!! Form::button('+', ['class' => 'btn btn-sm', 'id' => 'uploadAttach_afterimage']) !!}
                        <div id="lblFiles_afterimage">
                        </div>
                        {!! Form::hidden('files_string_afterimage', '', ['id' => 'files_string_afterimage']) !!}
                    @endif

                    {{--@if (Agent::isDesktop())--}}
                        {{--{!! Form::file('afterimage[]', ['multiple']) !!}--}}
                    {{--@else--}}
                        {{--{!! Form::button('+', ['class' => 'btn btn-sm', 'id' => 'btnSelectImage_afterimage']) !!}--}}
                    {{--@endif--}}
                @endif

            </div>
        </div>

        <div id="div_associatedapprovals">
            <div class="form-group">
                {!! Form::label('associatedapprovals', '关联扣款审批单:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}

                <div class='col-xs-8 col-sm-10'>
                    {!! Form::button('+', ['class' => 'btn btn-sm', 'data-toggle' => 'modal', 'data-target' => '#selectApprovalModal']) !!}
                    {!! Form::hidden('associatedapprovals', '', ['class' => 'btn btn-sm']) !!}
                    <div id="lblAssociatedapprovals">
                    </div>
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



