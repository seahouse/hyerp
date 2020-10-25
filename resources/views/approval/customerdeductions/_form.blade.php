<div class="reimb"><div class="form-d">

        <div class="form-group">
            {!! Form::label('customer_name', '客户名称:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text('customer_name', null, ['class' => 'form-control', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectCustomerModal']) !!}
                {!! Form::hidden('customer_id', 0, ['id' => 'customer_id']) !!}
            </div>
        </div>

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




        <div class="form-group">
            {!! Form::label('deductions_for', '扣款原因及明细:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::textarea('deductions_for', null, ['class' => 'form-control', $attr, 'rows' => 3]) !!}
            </div>
        </div>

        {{--<p class="bannerTitle">增补内容明细(1)</p>--}}

        {{--<div name="container_item">--}}
            {{--<div class="form-group">--}}
                {{--{!! Form::label('type', '增补内容:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}--}}
                {{--<div class='col-xs-8 col-sm-10'>--}}
                    {{--{!! Form::select('type', array('机务材料' => '机务材料', '机务设备' => '机务设备', '电气材料' => '电气材料', '电气设备' => '电气设备', '人工用量' => '人工用量', '运费' => '运费', '其他类别' => '其他类别'), null, ['class' => 'form-control', 'onchange' => 'selectTypeChange(this.dataset.num)', 'data-num' => '1', $attr, 'id' => 'type_1']) !!}--}}
                {{--</div>--}}
            {{--</div>--}}

            {{--<div id="divOtherremark_1">--}}
                {{--<div class="form-group">--}}
                    {{--{!! Form::label('otherremark', '其他类别补充说明:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}--}}
                    {{--<div class='col-xs-8 col-sm-10'>--}}
                        {{--{!! Form::text('otherremark', null, ['class' => 'form-control', $attr, 'id' => 'otherremark_1']) !!}--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}


            {{--<div class="form-group">--}}
                {{--{!! Form::label('unit', '单位:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}--}}
                {{--<div class='col-xs-8 col-sm-10'>--}}
                    {{--{!! Form::text('unit', null, ['class' => 'form-control', $attr, 'id' => 'unit_1']) !!}--}}
                {{--</div>--}}
            {{--</div>--}}




            {{--<div class="form-group">--}}
                {{--{!! Form::label('quantity', '数量:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}--}}
                {{--<div class='col-xs-8 col-sm-10'>--}}
                    {{--{!! Form::text('quantity', null, ['class' => 'form-control', 'placeholder' => '', $attr, 'id' => 'quantity_1']) !!}--}}
                {{--</div>--}}
            {{--</div>--}}


            {{--<div class="form-group">--}}
                {{--{!! Form::label('amount', '此项增补金额（元）:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}--}}
                {{--<div class='col-xs-8 col-sm-10'>--}}
                    {{--{!! Form::text('amount', null, ['class' => 'form-control', 'placeholder' => '', $attr, 'id' => 'amount_1']) !!}--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}


        {{--<div id="itemMore">--}}
        {{--</div>--}}
        {{--<a href="javascript:void(0);" class="bannerTitle addMore" id="btnAddItem">+增加明细</a>--}}

        {{--{!! Form::hidden('items_string', null, ['id' => 'items_string']) !!}--}}

        <div class="form-group">
            {!! Form::label('amount', '扣款金额（元）:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text('amount', null, ['class' => 'form-control', 'placeholder' => '请输入', $attr]) !!}
            </div>
        </div>


        <div class="form-group" id="divFiles">
            {!! Form::label('files', '附件:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}

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


        <div class="form-group" id="divImages">
            {!! Form::label('images', '图片:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}

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



