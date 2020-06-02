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
            <th>备注</th>
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
                    @if(!$field_match) {!! Form::button('设置字段', ['class' => 'btn btn-sm btn-danger', 'data-toggle' => 'modal', 'data-target' => '#resetFieldModal']) !!} @endif
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
                        {!! Form::text('unit', $constructionbidinformationitem->unit, ['class' => 'form-control', 'readonly']) !!}
                    </td>
                    <td>
                        {!! Form::text('remark', $constructionbidinformationitem->remark, ['class' => 'form-control', 'readonly']) !!}
                    </td>
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
                    <h4 class="modal-title">关联销售订单</h4>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '项目编号、项目名称', 'id' => 'keyProject']) !!}

                        <span class="input-group-btn">
                   		    {!! Form::button('查找', ['class' => 'btn btn-default btn-sm', 'id' => 'btnSearchProject']) !!}
                   	    </span>
                    </div>
                    {!! Form::hidden('name', null, ['id' => 'name']) !!}
                    <p>
                    <div class="list-group" id="listsalesorders">

                    </div>
                    </p>
                    <form id="formAccept">
                        {!! csrf_field() !!}
                        {!! Form::hidden('soheadid', 0, ['class' => 'form-control', 'id' => 'soheadid']) !!}
                        {!! Form::hidden('informationid', 0, ['class' => 'form-control', 'id' => 'informationid']) !!}
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
