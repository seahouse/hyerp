@extends('navbarerp')

@section('main')
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
                    {{ $constructionbidinformationitem->key }}
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

    {{--@foreach($constructionbidinformation->biddinginformationitems()->orderBy('sort')->get() as $constructionbidinformationitem)--}}
        {{--<div class="form-group">--}}
            {{--{!! Form::label($constructionbidinformationitem->key, $constructionbidinformationitem->key, ['class' => 'col-xs-4 col-sm-2 control-label']) !!}--}}
            {{--<div class='col-xs-4 col-sm-6'>--}}
                {{--{!! Form::text($constructionbidinformationitem->key, $constructionbidinformationitem->value, ['class' => 'form-control', 'readonly', 'oncopy' => 'return false', 'oncontextmenu' => 'return false']) !!}--}}
            {{--</div>--}}
            {{--<div class='col-xs-4 col-sm-4'>--}}
                {{--@can('basic_biddinginformation_remark')--}}
                    {{--@if (strlen($constructionbidinformationitem->remark) > 0)--}}
                        {{--{!! Form::textarea($constructionbidinformationitem->key, $constructionbidinformationitem->remark, ['class' => 'form-control', 'readonly', 'rows' => 3]) !!}--}}
                    {{--@else--}}
                        {{--{!! Form::text($constructionbidinformationitem->key, $constructionbidinformationitem->remark, ['class' => 'form-control', 'readonly']) !!}--}}
                    {{--@endif--}}
                {{--@endcan--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--@endforeach--}}

    {{--<div class="form-group">--}}
        {{--<div class="col-sm-offset-2 col-sm-10">--}}
            {{--<a href="{{ url('basic/biddinginformations/exportword/' . $constructionbidinformation->id) }}" class="btn btn-primary" target="_blank">导出Word</a>--}}
        {{--</div>--}}
    {{--</div>--}}
    {!! Form::close() !!}

@endsection
