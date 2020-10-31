<div class="reimb"><div class="form-d">

        <div class="form-group">
            {!! Form::label('prhead_number', '采购申请单:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                @if (isset($prhead))
                    {!! Form::text('prhead_number', $prhead->number, ['class' => 'form-control', $attr, 'readonly']) !!}
                    {!! Form::hidden('prhead_id', $prhead->id, ['id' => 'prhead_id']) !!}
                @else
                    {!! Form::text('prhead_number', $prtype->prhead->number, ['class' => 'form-control', $attr, 'readonly']) !!}
                    {!! Form::hidden('prhead_id', $prtype->prhead->id, ['id' => 'prhead_id']) !!}
                @endif
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('supplier_name', '供应商:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                @if (isset($prtype))
                    {!! Form::text('supplier_name', $prtype->supplier->name, ['class' => 'form-control', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectSupplierModal', 'data-name' => 'supplier_name', 'data-id' => 'supplier_id']) !!}
                    {!! Form::hidden('supplier_id', $prtype->supplier->id, ['id' => 'supplier_id']) !!}
                @else
                    {!! Form::text('supplier_name', null, ['class' => 'form-control', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectSupplierModal', 'data-name' => 'supplier_name', 'data-id' => 'supplier_id']) !!}
                    {!! Form::hidden('supplier_id', 0, ['id' => 'supplier_id']) !!}
                @endif
            </div>
        </div>

        {{--<div class="form-group">--}}
            {{--{!! Form::label('sohead_salesmanager', '项目所属销售经理:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}--}}
            {{--<div class='col-xs-8 col-sm-10'>--}}
                {{--{!! Form::text('sohead_salesmanager', null, ['class' => 'form-control', 'readonly', $attr]) !!}--}}
            {{--</div>--}}
        {{--</div>--}}





        {{--<div class="form-group">--}}
            {{--{!! Form::label('reason', '本项签增原因详细说明:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}--}}
            {{--<div class='col-xs-8 col-sm-10'>--}}
                {{--{!! Form::textarea('reason', null, ['class' => 'form-control', $attr, 'rows' => 3]) !!}--}}
            {{--</div>--}}
        {{--</div>--}}


        {{--<div class="form-group">--}}
            {{--{!! Form::label('remark', '备注:', ['class' => 'col-xs-4 col-sm-2 control-label' ]) !!}--}}
            {{--<div class='col-xs-8 col-sm-10'>--}}
                {{--{!! Form::text('remark', null, ['class' => 'form-control', 'placeholder' => '请输入', $attr]) !!}--}}
            {{--</div>--}}
        {{--</div>--}}




        {{--<div class="form-group" id="divFiles">--}}
            {{--{!! Form::label('files', '签增单上传（到钉钉审批）:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}--}}

            {{--<div class='col-xs-8 col-sm-10'>--}}
                {{--@if (isset($issuedrawing))--}}
                    {{--@foreach ($issuedrawing->drawingattachments() as $drawingattachment)--}}
                        {{--<a href="{!! URL($drawingattachment->path) !!}" target="_blank" id="showPaymentnode">{{ $drawingattachment->filename }}</a> <br>--}}
                    {{--@endforeach--}}
                {{--@else--}}
                    {{--                    {!! Form::file('files[]', ['multiple']) !!}--}}
                    {{--{!! Form::button('+', ['class' => 'btn btn-sm', 'id' => 'uploadAttach']) !!}--}}
                    {{--<div id="lblFiles">--}}
                    {{--</div>--}}
                    {{--{!! Form::hidden('files_string', null, ['id' => 'files_string']) !!}--}}
                {{--@endif--}}
            {{--</div>--}}
        {{--</div>--}}


<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
    {!! Form::submit($submitButtonText, ['class' => $btnclass, 'id' => 'btnSubmit']) !!}
    </div>
</div>
</div>
</div>



