<div class="reimb"><div class="form-d">

        <div class="form-group">
            {!! Form::label('supplier_name', '供应商:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                @if (isset($prtype))
                    {!! Form::text('supplier_name', $prtype->supplier->name, ['class' => 'form-control', $attr, 'readonly']) !!}
                @elseif(isset($prtypeitem))
                    {!! Form::text('supplier_name', $prtypeitem->prtype->supplier->name, ['class' => 'form-control', $attr, 'readonly']) !!}
                @endif
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('item_name', '物料:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                @if (isset($prtypeitem))
                    {!! Form::text('item_name', $prtypeitem->item->goods_name, ['class' => 'form-control', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectItemModal', 'readonly']) !!}
                    {!! Form::hidden('item_id', $prtypeitem->item->goods_id, ['id' => 'item_id']) !!}
                @else
                    {!! Form::text('item_name', null, ['class' => 'form-control', $attr, 'data-toggle' => 'modal', 'data-target' => '#selectItemModal']) !!}
                    {!! Form::hidden('item_id', 0, ['id' => 'item_id']) !!}
                @endif
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('item_unit_name', '单位:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                @if (isset($prtypeitem))
                    {!! Form::text('item_unit_name', $prtypeitem->item->goods_unit_name, ['class' => 'form-control', $attr, 'readonly']) !!}
                @else
                    {!! Form::text('item_unit_name', null, ['class' => 'form-control', $attr, 'readonly']) !!}
                @endif
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('quantity', '数量:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
            <div class='col-xs-8 col-sm-10'>
                {!! Form::text('quantity', null, ['class' => 'form-control', $attr]) !!}
            </div>
        </div>

        @if (isset($prtype))
            {!! Form::hidden('prtype_id', $prtype->id, ['id' => 'prtype_id']) !!}
        @elseif (isset($prtypeitem))
            {!! Form::hidden('prtype_id', $prtypeitem->prtype->id, ['id' => 'prtype_id']) !!}
        @endif



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



