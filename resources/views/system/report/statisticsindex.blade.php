@extends('navbarerp')

@section('main')
<div class="panel-heading">
    {{--
        <div class="pull-right" style="padding-top: 4px;">
            <a href="{{ URL::to('system/depts') }}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> {{'部门管理', [], 'layouts'}}</a>
</div>
--}}
</div>

<div class="panel-body">
    {!! Form::open(['url' => '/system/report/' . $report->id . '/export', 'class' => 'pull-right form-inline']) !!}
    <div class="form-group-sm">
        @foreach($input as $key=>$value)
        {!! Form::hidden($key, $value) !!}
        @endforeach
        {!! Form::submit('导出到Excel', ['class' => 'btn btn-default btn-sm']) !!}
    </div>
    {!! Form::close() !!}

    {!! Form::open(['url' => '/system/report/' . $report->id . '/statistics', 'class' => 'pull-right form-inline']) !!}
    <div class="form-group-sm">
        {{-- 根据不同报表设置不同搜索条件 --}}
        @if ($report->name == "po_warehouse_percent")
        {!! Form::label('arrivaldatelabel', '到货时间:', ['class' => 'control-label']) !!}
        {!! Form::date('datearravalfrom', null, ['class' => 'form-control']) !!}
        {!! Form::label('arrivaldatelabelto', '-', ['class' => 'control-label']) !!}
        {!! Form::date('datearravalto', null, ['class' => 'form-control']) !!}

        {!! Form::text('key', null, ['class' => 'form-control', 'placeholder' => '对应项目名称']) !!}
        @elseif ($report->name == "so_factory_analysis")
        @elseif ($report->name == "so_height_statistics_detail")
        {!! Form::select('orderid', $soheadList_hxold, null, ['class' => 'form-control', 'placeholder' => '--请选择--']) !!}
        @elseif ($report->name == "po_statistics")
        {!! Form::label('signdatelabel', '签订日期:', ['class' => 'control-label']) !!}
        {!! Form::date('signdatefrom', null, ['class' => 'form-control']) !!}
        {!! Form::label('signdatelabelto', '-', ['class' => 'control-label']) !!}
        {!! Form::date('signdateto', null, ['class' => 'form-control']) !!}
        {!! Form::select('arrivalstatus', array(0 => '未到货', 1 => '部分到货', 2 => '全部到货'), null, ['class' => 'form-control', 'placeholder' => '--到货状态--']) !!}
        {!! Form::select('paidstatus', array(0 => '未付款', 1 => '部分付款', 2 => '全部付款'), null, ['class' => 'form-control', 'placeholder' => '--付款状态--']) !!}
        {!! Form::select('ticketedstatus', array(0 => '未开票', 1 => '部分开票', 2 => '全部开票'), null, ['class' => 'form-control', 'placeholder' => '--开票状态--']) !!}
        {!! Form::text('goods_name', null, ['class' => 'form-control', 'placeholder' => '商品名称']) !!}
        @elseif ($report->name == "in_batch")
        {!! Form::text('batch', null, ['class' => 'form-control', 'placeholder' => '批号']) !!}
        @elseif ($report->name == "so_cost_statistics")
        {!! Form::select('orderid', $soheadList_hxold, null, ['class' => 'form-control', 'placeholder' => '--请选择--']) !!}
        @elseif ($report->name == "so_amount_statistics")
        {!! Form::select('dateyear', $poheadOrderDateyearList_hxold, null, ['class' => 'form-control', 'placeholder' => '--年份--']) !!}
        @elseif ($report->name == "so_projectengineeringlist_statistics")
        {!! Form::select('orderid', $myprojectListByProjectengineer, null, ['class' => 'form-control', 'placeholder' => '--项目--']) !!}
        @elseif ($report->name == "so_amountstatistics_forfinancedept")
        {!! Form::select('dateyear', $soheadOrderDateyearList_hxold, null, ['class' => 'form-control', 'placeholder' => '--年份--']) !!}
        @elseif ($report->name == "po_statistics_byproject")
        {!! Form::select('project_id', $projectList, null, ['class' => 'form-control', 'placeholder' => '--项目--']) !!}
        @elseif ($report->name == "so_bonuspayment")
        {!! Form::select('salesmanagerid', $salesmanagerList2, null, ['class' => 'form-control', 'placeholder' => '--销售经理--', 'id' => 'salesmanager']) !!}
        {!! Form::label('paymentdatelabel', '付款日期:', ['class' => 'control-label']) !!}
        {!! Form::date('paymentdatefrom', null, ['class' => 'form-control']) !!}
        {!! Form::label('paymentdatelabelto', '-', ['class' => 'control-label']) !!}
        {!! Form::date('paymentdateto', null, ['class' => 'form-control']) !!}
        @elseif ($report->name == "in_out_detail")
        {!! Form::select('type', array('weight' => '重量（KG）'), null, ['class' => 'form-control', 'placeholder' => '--类型--']) !!}
        {!! Form::label('project_name', '订单', ['class' => 'control-label']) !!}
        {!! Form::select('project_name', $projectList, null, ['class' => 'form-control', 'id' => 'select_project']) !!}
        {!! Form::hidden('project_id', null) !!}
        {{--{!! Form::label('sohead_name', '订单', ['class' => 'control-label']) !!}--}}
        {{--{!! Form::select('sohead_name', $soheadList_hxold, null, ['class' => 'form-control', 'id' => 'select_sohead']) !!}--}}
        {{--{!! Form::hidden('sohead_id', null, ['id' => 'sohead_id']) !!}--}}
        @elseif ($report->name == "in_in_detail")
        {!! Form::select('type', array('weight' => '重量（KG）'), null, ['class' => 'form-control', 'placeholder' => '--类型--']) !!}
        {!! Form::label('project_name', '订单', ['class' => 'control-label']) !!}
        {!! Form::select('project_name', $projectList, null, ['class' => 'form-control', 'id' => 'select_project']) !!}
        {!! Form::hidden('project_id', null) !!}
        {{--{!! Form::label('sohead_name', '订单', ['class' => 'control-label']) !!}--}}
        {{--{!! Form::select('sohead_name', $soheadList_hxold, null, ['class' => 'form-control', 'id' => 'select_sohead']) !!}--}}
        {{--{!! Form::hidden('sohead_id', null, ['id' => 'sohead_id']) !!}--}}
        @elseif ($report->name == "dt_logs_xmjlsgrz_detail")
        {!! Form::label('project_name', '项目', ['class' => 'control-label']) !!}
        {!! Form::select('project_name', $projectList, null, ['class' => 'form-control', 'id' => 'select_project']) !!}
        {!! Form::hidden('project_id', null) !!}
        @elseif ($report->name == "dt_logs_xmjlsgrz")
        {!! Form::label('project_name', '项目', ['class' => 'control-label']) !!}
        {!! Form::select('project_name', $projectList, null, ['class' => 'form-control', 'id' => 'select_project']) !!}
        {!! Form::hidden('project_id', null) !!}
        @elseif ($report->name == "pgetWarehouseDetailByorder")
        {!! Form::text('goodsname', null, ['class' => 'form-control','placeholder'=>'商品名称', 'id' => 'goodsname']) !!}
        {!! Form::text('goodsspec', null, ['class' => 'form-control','placeholder'=>'规格', 'id' => 'goodsspec']) !!}
        {!! Form::text('vendorname', null, ['class' => 'form-control','placeholder'=>'供应商', 'id' => 'vendorname']) !!}
        {!! Form::hidden('orderid', $input['orderid']) !!}
        @elseif ($report->name == "pgetOtherWarehouseDetailByorder")
        {!! Form::text('goodsname', null, ['class' => 'form-control','placeholder'=>'商品名称', 'id' => 'goodsname']) !!}
        {!! Form::text('goodsspec', null, ['class' => 'form-control','placeholder'=>'规格', 'id' => 'goodsspec']) !!}
        {!! Form::text('vendername', null, ['class' => 'form-control','placeholder'=>'供应商', 'id' => 'vendername']) !!}
        {!! Form::hidden('orderid', $input['orderid']) !!}
        @elseif ($report->name == "pgetInventoryDetailByorder")
        {!! Form::text('goodsname', null, ['class' => 'form-control','placeholder'=>'商品名称', 'id' => 'goodsname']) !!}
        {!! Form::text('goodsspec', null, ['class' => 'form-control','placeholder'=>'规格', 'id' => 'goodsspec']) !!}
        {!! Form::text('vendername', null, ['class' => 'form-control','placeholder'=>'供应商', 'id' => 'vendername']) !!}
        {!! Form::hidden('orderid', $input['orderid']) !!}
        @elseif ($report->name == "pgetFromOtherWarehouseDetailByorder")
        {!! Form::text('goodsname', null, ['class' => 'form-control','placeholder'=>'商品名称', 'id' => 'goodsname']) !!}
        {!! Form::text('goodsspec', null, ['class' => 'form-control','placeholder'=>'规格', 'id' => 'goodsspec']) !!}
        {!! Form::text('vendername', null, ['class' => 'form-control','placeholder'=>'供应商', 'id' => 'vendername']) !!}
        {!! Form::hidden('orderid', $input['orderid']) !!}
        @elseif ($report->name == "pgetWarehouseDetailByproject")
        {!! Form::text('goodsname', null, ['class' => 'form-control','placeholder'=>'商品名称', 'id' => 'goodsname']) !!}
        {!! Form::text('goodsspec', null, ['class' => 'form-control','placeholder'=>'规格', 'id' => 'goodsspec']) !!}
        {!! Form::text('vendorname', null, ['class' => 'form-control','placeholder'=>'供应商', 'id' => 'vendorname']) !!}
        {!! Form::hidden('projectid', $input['projectid']) !!}
        @elseif ($report->name == "pgetOtherWarehouseDetailByproject")
        {!! Form::text('goodsname', null, ['class' => 'form-control','placeholder'=>'商品名称', 'id' => 'goodsname']) !!}
        {!! Form::text('goodsspec', null, ['class' => 'form-control','placeholder'=>'规格', 'id' => 'goodsspec']) !!}
        {!! Form::text('vendername', null, ['class' => 'form-control','placeholder'=>'供应商', 'id' => 'vendername']) !!}
        {!! Form::hidden('projectid', $input['projectid']) !!}
        @elseif ($report->name == "pgetInventoryDetailByproject")
        {!! Form::text('goodsname', null, ['class' => 'form-control','placeholder'=>'商品名称', 'id' => 'goodsname']) !!}
        {!! Form::text('goodsspec', null, ['class' => 'form-control','placeholder'=>'规格', 'id' => 'goodsspec']) !!}
        {!! Form::text('vendername', null, ['class' => 'form-control','placeholder'=>'供应商', 'id' => 'vendername']) !!}
        {!! Form::hidden('projectid', $input['projectid']) !!}
        @elseif ($report->name == "pgetFromOtherWarehouseDetailByproject")
        {!! Form::text('goodsname', null, ['class' => 'form-control','placeholder'=>'商品名称', 'id' => 'goodsname']) !!}
        {!! Form::text('goodsspec', null, ['class' => 'form-control','placeholder'=>'规格', 'id' => 'goodsspec']) !!}
        {!! Form::text('vendername', null, ['class' => 'form-control','placeholder'=>'供应商', 'id' => 'vendername']) !!}
        {!! Form::hidden('projectid', $input['projectid']) !!}
        @elseif ($report->name == "po_costincrease_detail")
        {!! Form::label('sohead_name', '订单', ['class' => 'control-label']) !!}
        {!! Form::select('sohead_name', $soheadList_hxold, null, ['class' => 'form-control', 'id' => 'select_sohead']) !!}
        {!! Form::hidden('sohead_id', null, ['id' => 'sohead_id']) !!}
        @elseif ($report->name == "po_supplier_amount")
        <label for="year_begin">年份</label>
        <input type="number" id="year_begin" name="year_begin" class="form-control" min="{{ $year_arr['min'] }}" max="{{ $year_arr['max'] }}" @if(isset($input['year_begin'])) value="{{ $input['year_begin'] }}" @endif>
        <span>-</span>
        <input type="number" id="year_end" name="year_end" class="form-control" min="{{ $year_arr['min'] }}" max="{{ $year_arr['max'] }}" @if(isset($input['year_end'])) value="{{ $input['year_end'] }}" @endif>
        @elseif ($report->name == "in_split_out_bynumber_008")
        {!! Form::text('number', null, ['class' => 'form-control','placeholder'=>'出库单号', 'id' => 'number']) !!}
        @elseif ($report->name == "ap_epcsecening_statistics")
            {!! Form::label('sohead_name', '订单', ['class' => 'control-label']) !!}
            {!! Form::select('sohead_name', $soheadList_hxold, null, ['class' => 'form-control', 'id' => 'select_sohead']) !!}
            {!! Form::hidden('sohead_id', null, ['id' => 'sohead_id']) !!}
        @endif

        <?php $showSearch = true; ?>
        @if ($report->name == "in_split_out_bynumber_008")
        @cannot('inventory_out_splitoutbynumber008')
        <?php $showSearch = false; ?>
        @endcan
        @endif
        @if ($showSearch)
        {!! Form::submit('查找', ['class' => 'btn btn-default btn-sm']) !!}
        @endif
    </div>
    {!! Form::close() !!}
</div>

<?php $hasright = false; ?>
@if ($report->name == "so_projectengineeringlist_statistics")
@can('system_report_so_projectengineeringlist_statistics')
<?php $hasright = true; ?>
@endcan
@elseif ($report->name == "so_amountstatistics_forfinancedept")
@can('system_report_so_amountstatistics_forfinancedept')
<?php $hasright = true; ?>
@endcan
@elseif ($report->name == "in_out_detail")
@can('inventory_out_detail')
<?php $hasright = true; ?>
@endcan
@elseif ($report->name == "in_split_out_bynumber_008")
@can('inventory_out_splitoutbynumber008')
<?php $hasright = true; ?>
@endcan
@elseif ($report->name == "in_in_detail")
@can('inventory_in_detail')
<?php $hasright = true; ?>
@endcan
@elseif ($report->name == "ap_epcsecening_statistics")
    @can('approval_epcsecening_statistics')
        <?php $hasright = true; ?>
    @endcan
@else
@if (Auth::user()->isSuperAdmin())
<?php $hasright = true; ?>
@endif
@endif

@if ($hasright)
@if ($items->count())
<table class="table table-striped table-hover table-condensed">
    <thead>
        <tr>
            @if (count($titleshows) > 1)
            @foreach($titleshows as $titleshow)
            <th>{{ $titleshow }}</th>
            @endforeach
            @else
            @foreach(array_first($items->items()) as $key=>$value)
            <th>{{$key}}</th>
            @endforeach
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
        <tr>
            @foreach($item as $value)
            <td>
                {{ $value }}
            </td>
            @endforeach
        </tr>
        @endforeach

        @if (count($sumcols) > 0 && strlen($sumcols[0]) > 0)
        <?php $sumvalues = []; ?>

        @foreach($items as $item)
        <?php $colnum = 1; ?>
        @foreach($item as $value)
        @foreach ($sumcols as $key => $sumcol)
        @if ($colnum == $sumcol)
        <?php $sumvalues[$key] = array_key_exists($key, $sumvalues) ? $sumvalues[$key] + $value : $value; ?>
        @endif
        @endforeach

        <?php $colnum++; ?>
        @endforeach
        @endforeach

        <tr class="info">
            @foreach($items as $item)
            <?php $colnum = 1; ?>
            @foreach($item as $value)
            <td>
                @foreach ($sumcols as $key => $sumcol)
                @if ($colnum == $sumcol)
                {{ $sumvalues[$key] }}
                @endif
                @endforeach
                <?php $colnum++; ?>
            </td>
            @endforeach
            @break
            @endforeach

        </tr>

        <tr class="success">
            @foreach($items as $item)
            <?php $colnum = 1; ?>
            <?php $totalindex = 0; ?>
            @foreach($item as $value)
            <td>
                @foreach ($sumcols as $key => $sumcol)
                @if ($colnum == $sumcol)
                @if (count($sumvalues_total) > $key)
                {{ $sumvalues_total[$sumcol] }}
                @endif
                @endif
                @endforeach
                <?php $colnum++; ?>
            </td>
            @endforeach
            @break
            @endforeach
        </tr>
        @endif
    </tbody>

</table>
{!! $items->setPath('/system/report/' . $report->id . '/statistics')->appends($input)->links() !!}
@else
<div class="alert alert-warning alert-block">
    <i class="fa fa-warning"></i>
    {{'无记录', [], 'layouts'}}
</div>
@endif
@else
无权限。
@endif
@stop

@section('script')
<script type="text/javascript" src="/js/jquery-editable-select.js"></script>
{{--<script type="text/javascript" src="/DataTables/DataTables-1.10.16/js/jquery.dataTables.js"></script>--}}
<script type="text/javascript">
    jQuery(document).ready(function(e) {

        $('#select_project')
            .editableSelect({
                effects: 'slide',
            })

            .on('select.editable-select', function(e, li) {
                if (li.val() > 0)
                    $('input[name=project_id]').val(li.val());
                else
                    $('input[name=project_id]').val('');
            });

        $('#select_sohead')
            .editableSelect({
                effects: 'slide',
            })

            .on('select.editable-select', function(e, li) {
                if (li.val() > 0)
                    $('input[name=sohead_id]').val(li.val());
                else
                    $('input[name=sohead_id]').val('');
            });
    });
</script>
@endsection