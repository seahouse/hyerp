<div class="reimb"><div class="form-d">

        <div class="form-group">
            {!! Form::label('pohead_number', '采购合同:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text('pohead_number', null, ['class' => 'form-control', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectOrderModal', 'data-name' => 'pohead_number', 'data-id' => 'pohead_id', 'data-supplierid' => 'supplier_id', 'data-poheadamount' => 'pohead_amount']) !!}
                {!! Form::hidden('pohead_id', 0, ['class' => 'btn btn-sm', 'id' => 'pohead_id']) !!}
                @if (isset($reimbursement->customer_hxold->name))
                    {!! Form::hidden('customer_name2', $reimbursement->customer_hxold->name, ['class' => 'btn btn-sm', 'id' => 'customer_name2']) !!}
                @else
                    {!! Form::hidden('customer_name2', null, ['class' => 'btn btn-sm', 'id' => 'customer_name2']) !!}
                @endif
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('pohead_descrip', '对应工程名称:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text('pohead_descrip', null, ['class' => 'form-control', 'readonly', $attr]) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('sohead_number', '对应工程编号:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text('sohead_number', null, ['class' => 'form-control', 'readonly', $attr]) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('vendor_name', '外协单位名称:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text('vendor_name', null, ['class' => 'form-control', 'readonly', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectSupplierModal', 'data-name' => 'supplier_name', 'data-id' => 'supplier_id']) !!}
                {!! Form::hidden('vendor_id', 0, ['class' => 'btn btn-sm', 'id' => 'supplier_id']) !!}
            </div>
        </div>

        {{--<div class="form-group">--}}
            {{--{!! Form::label('project_name', '所属订单:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}--}}
            {{--<div class='col-xs-8 col-sm-10'>--}}
                {{--{!! Form::text('project_name', $project_name, ['class' => 'form-control', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectProjectModal', 'data-name' => 'project_name_1', 'data-id' => 'sohead_id_1', 'data-num' => '1', 'id' => 'project_name_1']) !!}--}}
                {{--{!! Form::hidden('sohead_id', 0, ['class' => 'btn btn-sm', 'id' => 'sohead_id_1']) !!}--}}
            {{--</div>--}}
        {{--</div>--}}

        {{--<div class="form-group">--}}
            {{--{!! Form::label('sohead_number', '订单编号:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}--}}
            {{--<div class='col-xs-8 col-sm-10'>--}}
                {{--{!! Form::text('sohead_number', null, ['class' => 'form-control', 'readonly', $attr, 'id' => 'sohead_number_1']) !!}--}}
            {{--</div>--}}
        {{--</div>--}}



        <div class="form-group">
            {!! Form::label('outsourcingtype', '外协单位所属种类:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::select('outsourcingtype', array('安装公司' => '安装公司', '机务设备类外协单位' => '机务设备类外协单位', '电气类外协单位' => '电气类外协单位', '泰州生产中心生产队伍' => '泰州生产中心生产队伍',
                    '胶州生产中心生产队伍' => '胶州生产中心生产队伍', '宣城生产中心生产队伍' => '宣城生产中心生产队伍', '许昌生产中心生产队伍' => '许昌生产中心生产队伍'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable]) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('techdepart', '工艺主设部门:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::select('techdepart', array('工艺一室' => '工艺一室', '工艺二室' => '工艺二室', '工艺三室' => '工艺三室'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable]) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('problemlocation', '扣款问题发生地:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::select('problemlocation', array('项目现场' => '项目现场', '生产中心' => '生产中心', '仓库' => '仓库'), null, ['class' => 'form-control', 'placeholder' => '--请选择--', $attr, $attrdisable]) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('reason', '扣款原因:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::textarea('reason', null, ['class' => 'form-control', $attr, 'rows' => 3]) !!}
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



@else








            <p class="bannerTitle">明细(1)</p>

            <div name="container_item">

                <div class="form-group">
                    {!! Form::label('itemname', '设备名称:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                    <div class='col-xs-8 col-sm-10'>
                        {!! Form::text('itemname', null, ['class' => 'form-control', $attr, 'id' => 'itemname_1']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('itemspec', '规格:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                    <div class='col-xs-8 col-sm-10'>
                        {!! Form::text('itemspec', null, ['class' => 'form-control', $attr, 'id' => 'itemspec_1']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('itemunit', '单位:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                    <div class='col-xs-8 col-sm-10'>
                        {!! Form::text('itemunit', null, ['class' => 'form-control', $attr, 'id' => 'itemunit_1']) !!}
                    </div>
                </div>


                <div class="form-group">
                    {!! Form::label('quantity', '数量:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                    <div class='col-xs-8 col-sm-10'>
                        {!! Form::text('quantity', null, ['class' => 'form-control', 'placeholder' => '', $attr, 'id' => 'quantity_1']) !!}
                    </div>
                </div>


                <div class="form-group">
                    {!! Form::label('unitprice', '单价:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                    <div class='col-xs-8 col-sm-10'>
                        {!! Form::text('unitprice', null, ['class' => 'form-control', 'placeholder' => '', $attr, 'id' => 'unitprice_1']) !!}
                    </div>
                </div>

                {{--<div class="form-group">--}}
                    {{--{!! Form::label('price', '金额（元）:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}--}}
                    {{--<div class='col-xs-8 col-sm-10'>--}}
                        {{--{!! Form::text('price', null, ['class' => 'form-control', 'placeholder' => '', $attr, 'id' => 'price_1']) !!}--}}
                    {{--</div>--}}
                {{--</div>--}}

            </div>




            <div id="itemMore">
            </div>
            {{--{!! Form::button('+增加明细', ['class' => 'btn btn-sm', 'id' => 'btnAddTravel']) !!}--}}
            <a href="javascript:void(0);" class="bannerTitle addMore" id="btnAddItem">+增加明细</a>













        </div>




            <div id="itemMore">
            </div>








@endif
        {!! Form::hidden('items_string', null, ['id' => 'items_string']) !!}




        {{--<div class="form-group">--}}
            {{--{!! Form::label('totalprice', '合计总金额:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}--}}
            {{--<div class='col-xs-8 col-sm-10'>--}}
                {{--{!! Form::text('totalprice', null, ['class' => 'form-control', 'placeholder' => '会根据单价明细自动计算', $attr, 'readonly']) !!}--}}
            {{--</div>--}}
        {{--</div>--}}



    {{--<div class="form-group">--}}
        {{--{!! Form::label('contact', '联系人:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}--}}
        {{--<div class='col-xs-8 col-sm-10'>--}}
            {{--{!! Form::text('contact', null, ['class' => 'form-control', 'placeholder' => '请输入拟采购公司联系人姓名', $attr]) !!}--}}
        {{--</div>--}}
    {{--</div>--}}

    {{--<div class="form-group">--}}
        {{--{!! Form::label('phonenumber', '手机号码:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}--}}
        {{--<div class='col-xs-8 col-sm-10'>--}}
            {{--{!! Form::text('phonenumber', null, ['class' => 'form-control', 'placeholder' => '请输入拟采购联系人手机', $attr]) !!}--}}
        {{--</div>--}}
    {{--</div>--}}

    <div class="form-group">
        {!! Form::label('remark', '备注:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}
        <div class='col-xs-8 col-sm-10'>
            {!! Form::textarea('remark', null, ['class' => 'form-control', $attr, 'rows' => 3]) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('files', '供应商盖章或签字确认的文件:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}

        <div class='col-xs-8 col-sm-10'>
            @if (isset($issuedrawing))
                @foreach ($issuedrawing->drawingattachments() as $drawingattachment)
                    <a href="{!! URL($drawingattachment->path) !!}" target="_blank" id="showPaymentnode">{{ $drawingattachment->filename }}</a> <br>
                @endforeach
            @else
                {!! Form::file('files[]', ['multiple']) !!}
                {!! Form::button('+', ['class' => 'btn btn-sm', 'id' => 'uploadAttach']) !!}
                {!! Form::hidden('files_string', null, ['id' => 'files_string']) !!}
            @endif
        </div>
    </div>



    <div class="form-group">
        {!! Form::label('images', '供应商确认的或执行通知义务的截图:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}

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


{!! Form::hidden('applicant_id', null, ['class' => 'btn btn-sm']) !!}
{!! Form::hidden('approversetting_id', null, ['class' => 'btn btn-sm']) !!}

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
    {!! Form::submit($submitButtonText, ['class' => $btnclass, 'id' => 'btnSubmit']) !!}
    </div>
</div>
</div>
</div>



