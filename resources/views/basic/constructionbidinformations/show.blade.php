@extends('navbarerp')

@section('main')
    <?php use App\Models\Basic\Constructionbidinformationfield; ?>

    {!! Form::model($constructionbidinformation, ['class' => 'form-horizontal']) !!}
    @include('basic.constructionbidinformations._form',
        [
            'submitButtonText' => '提交',
            'datepay' => null,
            'requestdeliverydate' => null,
            'customer_name' => null,
            'customer_id' => null,
            'amount' => null,
            'order_number' => null,
            'order_id' => null,
            'datego' => null,
            'dateback' => null,
            'mealamount' => null,
            'ticketamount' => null,
            'amountAirfares' => null,
            'amountTrain' => null,
            'amountTaxi' => null,
            'amountOtherTicket' => null,
            'stayamount' => null,
            'otheramount' => null,
            'attr' => 'readonly',
            'attrdisable' => 'disabled',
            'btnclass' => 'hidden',
        ])

    <table id="tableItems" class="table table-striped table-hover table-full-width">
        <thead>
        <tr>
            <th>项目类型</th>
            <th>名称</th>
            <th>采购方</th>
            <th>规格及技术要求</th>
            <th>单条线</th>
            <th>倍数</th>
            {{--<th>三条线</th>--}}
            {{--<th>四条线</th>--}}
            <th>单位</th>
            <!-- <th>备注</th> -->
        </tr>
        </thead>
        <tbody>
        @foreach($constructionbidinformation->constructionbidinformationitems as $constructionbidinformationitem)
            <tr data-constructionbidinformationitem_id="{{ $constructionbidinformationitem->id }}">
                <td>
                    {{ $constructionbidinformationitem->projecttype }}
                </td>
                <td>
                    {{ $constructionbidinformationitem->key }}
                    <?php $field_match = false;
                    $constructionbidinformationfield = Constructionbidinformationfield::where('name', $constructionbidinformationitem->key)->where('projecttype', $constructionbidinformationitem->projecttype)->first();
                    if (isset($constructionbidinformationfield))
                        $field_match = true;
                    ?>
                    @if(!$field_match) {!! Form::button('设置字段', ['class' => 'btn btn-sm btn-danger', 'data-toggle' => 'modal', 'data-target' => '#resetFieldModal', 'data-constructionbidinformationitem_id' => $constructionbidinformationitem->id]) !!} @endif
                </td>
                <div id="div{{ $constructionbidinformationitem->id }}" name="constructionbidinformationitem_container" data-constructionbidinformationitem_id="{{ $constructionbidinformationitem->id }}">
                    <td>
                        {!! Form::text('purchaser', $constructionbidinformationitem->purchaser, ['class' => 'form-control', 'readonly']) !!}
                    </td>
                    <td>
                        {!! Form::text('specification_technicalrequirements', $constructionbidinformationitem->specification_technicalrequirements, ['class' => 'form-control', 'readonly']) !!}
                    </td>
                    <td>
                        {!! Form::text('value', $constructionbidinformationitem->value, ['class' => 'form-control', 'readonly']) !!}
                    </td>
                    <td>
                        {!! Form::text('multiple', $constructionbidinformationitem->multiple, ['class' => 'form-control', 'readonly']) !!}
                    </td>
                    {{--<td>--}}
                        {{--{!! Form::text('value_line3', $constructionbidinformationitem->value_line3, ['class' => 'form-control', 'readonly']) !!}--}}
                    {{--</td>--}}
                    {{--<td>--}}
                        {{--{!! Form::text('value_line4', $constructionbidinformationitem->value_line4, ['class' => 'form-control', 'readonly']) !!}--}}
                    {{--</td>--}}
                    <td>
                        @if (null != $constructionbidinformationitem->constructionbidinformationfield())
                        {!! Form::text('unit', $constructionbidinformationitem->constructionbidinformationfield()->unit, ['class' => 'form-control', 'readonly']) !!}
                            @else
                            {!! Form::text('unit', '-', ['class' => 'form-control', 'readonly']) !!}
                        @endif
                    </td>
                    <!-- <td>
                        {!! Form::text('remark', $constructionbidinformationitem->remark, ['class' => 'form-control', 'readonly']) !!}
                    </td> -->
                </div>
            </tr>
        @endforeach
        {!! Form::hidden('items_string', null, ['id' => 'items_string']) !!}

        </tbody>
    </table>


    {{--<div class="form-group">--}}
        {{--<div class="col-sm-offset-2 col-sm-10">--}}
            {{--<a href="{{ url('basic/biddinginformations/exportword/' . $constructionbidinformation->id) }}" class="btn btn-primary" target="_blank">导出Word</a>--}}
        {{--</div>--}}
    {{--</div>--}}
    {!! Form::close() !!}

    <div class="modal fade" id="resetFieldModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">设置字段</h4>
                </div>
                <div class="modal-body">
                    {!! Form::open(array('url' => 'basic/constructionbidinformationitems/resetfield', 'class' => 'form-horizontal', 'id' => 'frmResetField')) !!}
                    <div class="form-group">
                        {!! Form::label('projecttype', '项目类型:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                        <div class='col-xs-8 col-sm-10'>
                            {!! Form::select('projecttype', $projecttypes_constructionbidinformationfield, null, ['class' => 'form-control', 'placeholder' => '--项目类型--', 'onchange' => 'selectTypeChange()']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('fieldname', '字段名称:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
                        <div class='col-xs-8 col-sm-10'>
                            {!! Form::select('fieldname', array(), null, ['class' => 'form-control', 'placeholder' => '--字段名称--']) !!}
                        </div>
                    </div>

                    {!! Form::hidden('constructionbidinformationitem_id', null, ['id' => 'constructionbidinformationitem_id']) !!}
                    {!! csrf_field() !!}
                    {!! Form::close() !!}


                    <div class="modal-footer">
                        {!! Form::button('取消', ['class' => 'btn btn-sm', 'data-dismiss' => 'modal']) !!}
                        {!! Form::button('确定', ['class' => 'btn btn-sm btn-primary', 'id' => 'btnResetField']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        jQuery(document).ready(function(e) {
            $('#resetFieldModal').on('show.bs.modal', function (e) {
                var text = $(e.relatedTarget);
                var modal = $(this);

                modal.find('#constructionbidinformationitem_id').val(text.data('constructionbidinformationitem_id'));
            });

            selectTypeChange = function () {
                var projecttype = $("#frmResetField").find("select[name='projecttype']").val();
//                alert(projecttype);

                $.post("{{ url('basic/constructionbidinformationfields/getfieldsbyprojecttype') }}", { projecttype: projecttype, _token: '{{ csrf_token() }}' }, function (data) {
                    //
                    $("#frmResetField").find("select[name='fieldname']").empty().append(data);
//                    $("#pppaymentitemtypecontainer_" + String(num)).empty().append(data);
                });
            };

            $("#btnResetField").click(function() {
                $("form#frmResetField").submit();
            });
        });
    </script>
@endsection
