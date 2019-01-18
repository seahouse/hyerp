@extends('navbarerp')

@section('title', '图纸物料清单')

<style>
    td.details-control {
        background: url('/resources/details_open.png') no-repeat center center;
        cursor: pointer;
    }
    tr.shown td.details-control {
        background: url('/resources/details_close.png') no-repeat center center;
    }
</style>

@section('main')
    <div class="panel-heading">
        <div class="panel-title">销售 -- 销售订单 -- 图纸物料清单
            {{--
            <div class="pull-right">
                <a href="{{ URL::to('sales/salesreps') }}" class="btn btn-sm btn-success">{{'销售代表管理'}}</a>
                <a href="{{ URL::to('sales/terms') }}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> {{'付款条款管理', [], 'layouts'}}</a>
            </div>
            --}}
        </div>
    </div>
    
    <div class="panel-body">
        {{--
        <a href="{{ URL::to('/sales/salesorders/create') }}" class="btn btn-sm btn-success">新建</a>
           --}}

        {{--
        {!! Form::open(['url' => '/sales/salesorderhx/search', 'class' => 'pull-right form-inline']) !!}
        <div class="form-group-sm">
            {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '订单编号、项目名称']) !!}
            {!! Form::submit('查找', ['class' => 'btn btn-default btn-sm']) !!}
        </div>
        {!! Form::close() !!}
        --}}

        
    </div>

    @if ($dwgboms->count())
    <table class="table table-striped table-hover table-condensed" id="tableData">
        <thead>
            <tr>
                <th></th>
                <th>物料清单名称</th>
                <th>生成时间</th>
                <th>更新时间</th>
            </tr>
        </thead>
        {{--<tbody>--}}
            {{--@foreach($dwgboms as $dwgbom)--}}
                {{--<tr>--}}
                    {{--<td>--}}
                        {{--{{ $dwgbom->bomname }}--}}
                    {{--</td>--}}
                    {{--<td>--}}

                    {{--</td>--}}
                    {{--<td>--}}

                    {{--</td>--}}
{{--</tr>--}}
{{--@endforeach--}}
{{--</tbody>--}}

</table>
    @if (isset($inputs))
        {!! $dwgboms->setPath('/sales/salesorderhx/' . $id . '/dwgbom')->appends($inputs)->links() !!}
    @else
        {!! $dwgboms->setPath('/sales/salesorderhx/' . $id . '/dwgbom')->links() !!}
    @endif
@else
<div class="alert alert-warning alert-block">
<i class="fa fa-warning"></i>
{{'无记录', [], 'layouts'}}
</div>
@endif

@endsection

@section('script')
    <script type="text/javascript" src="/DataTables/datatables.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function(e) {
//            $("#btnExport").click(function() {
//                $("form#formExport").find('#salesmanager').val($('input[name=salesmanager]').val());
//                $("form#formExport").find('input[name=receivedatestart]').val($("form#frmSearch").find('input[name=receivedatestart]').val());
//                $("form#formExport").find('input[name=receivedateend]').val($("form#frmSearch").find('input[name=receivedateend]').val());
//                $("form#formExport").submit();
//            });
//
//            $("#btnExport2").click(function() {
//                $("form#formExport2").find('#salesmanager').val($('input[name=salesmanager]').val());
//                $("form#formExport2").find('input[name=receivedatestart]').val($("form#frmSearch").find('input[name=receivedatestart]').val());
//                $("form#formExport2").find('input[name=receivedateend]').val($("form#frmSearch").find('input[name=receivedateend]').val());
//                $("form#formExport2").submit();
//            });

            function format ( d ) {
                // `d` is the original data object for the row
                return '<table class="table details-table" id="detail-' + d.id + '">'+
                    '<thead>'+
                    '<tr>' +
                    '<th>物料名称</th>' +
                    '<th>代码</th>' +
                    '<th>数量</th>' +
                    '<th>材料</th>' +
                    '<th>单重</th>' +
                    '<th>总重</th>' +
                    '<th>备注</th>' +
                    '</tr>'+
                    '</thead>'+
                    '</table>';
            }

//            var template = Handlebars.compile($("#details-template").html());
            var table = $('#tableData').DataTable({
                "processing": true,
                "serverSide": true,
                {{--"ajax": "{{ url('my/bonus/indexjsonbyorder') }}",--}}
                "ajax": {
                    "url": "{{ url('sales/salesorderhx/dwgbomjson') }}",
                    "data": function (d) {
                        d.sohead_id = {!! $id !!};
//                        d.receivedatestart = $("form#frmSearch").find('input[name=receivedatestart]').val();
//                        d.receivedateend = $("form#frmSearch").find('input[name=receivedateend]').val();
                    }
                },
                "columns": [
                    {
                        "className":      'details-control',
                        "orderable":      false,
                        "searchable":      false,
                        "data":           null,
                        "defaultContent": ''
                    },
                    {"data": "bomname", "name": "bomname"},
                    {"data": "create_at", "name": "create_at"},
                    {"data": "update_at", "name": "update_at"},
//                    {"data": "salesmanager", "name": "salesmanager"},
//                    {"data": "amountperiod2", "name": "amountperiod2"},
//                    {"data": "bonusfactor", "name": "bonusfactor"},
//                    {"data": "bonus", "name": "bonus"},
//                    {"data": "bonuspaid", "name": "bonuspaid"},
//                    {"data": "paybonus", "name": "paybonus"},
                ],
//                "fnCreatedRow": function(nRow, aData, iDataIndex) {
//                    $('td:eq(0)', nRow).html("<span class='row-details row-details-close' data_id='" + aData[1] + "'></span>&nbsp;" + aData[0]);
//                }
            });

            $('#tableData tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                var tableId = 'detail-' + row.data().id;

                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    // Open this row
                    row.child(format(row.data())).show();
//                    row.child(template(row.data())).show();
                    initTable(tableId, row.data());
                    tr.addClass('shown');
                    tr.next().find('td').addClass('no-padding bg-gray');
                }
            } );

            function initTable(tableId, data) {
                $('#' + tableId).DataTable({
                    processing: true,
                    serverSide: true,
                    {{--ajax: "{{ url('my/bonus/detailjsonbyorder') }}/" + data.id,--}}
                    "ajax": {
                        "url": "{{ url('sales/salesorderhx/dwgbomjsondetail') }}/" + data.id,
                        "data": function (d) {
                            d.receivedatestart = $('input[name=receivedatestart]').val();
                            d.receivedateend = $('input[name=receivedateend]').val();
                        }
                    },
                    columns: [
                        { data: 'goods_name', name: 'goods_name' },
                        { data: 'code', name: 'code' },
                        { data: 'quantity', name: 'quantity' },
                        { data: 'meterial', name: 'meterial' },
                        { data: 'unitweight', name: 'unitweight' },
                        { data: 'weight', name: 'weight' },
                        { data: 'remark', name: 'remark' },
                    ]
                })
            };

            $('#frmSearch').on('submit', function (e) {
                table.draw();
                e.preventDefault();
            })
        });
    </script>
@endsection
