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
                {!! Form::select('orderid', $poheadList_hxold, null, ['class' => 'form-control', 'placeholder' => '--请选择--']) !!}
            @elseif ($report->name == "po_statistics")
                {!! Form::label('signdatelabel', '签订日期:', ['class' => 'control-label']) !!}
                {!! Form::date('signdatefrom', null, ['class' => 'form-control']) !!}
                {!! Form::label('signdatelabelto', '-', ['class' => 'control-label']) !!}
                {!! Form::date('signdateto', null, ['class' => 'form-control']) !!}
                {!! Form::select('arrivalstatus', array(0 => '未到货', 1 => '部分到货', 2 => '全部到货'), null, ['class' => 'form-control', 'placeholder' => '--到货状态--']) !!}
                {!! Form::select('paidstatus', array(0 => '未付款', 1 => '部分付款', 2 => '全部付款'), null, ['class' => 'form-control', 'placeholder' => '--付款状态--']) !!}
                {!! Form::select('ticketedstatus', array(0 => '未开票', 1 => '部分开票', 2 => '全部开票'), null, ['class' => 'form-control', 'placeholder' => '--开票状态--']) !!}
            @elseif ($report->name == "in_batch")
                {!! Form::text('batch', null, ['class' => 'form-control', 'placeholder' => '批号']) !!}
            @elseif ($report->name == "so_cost_statistics")
                {!! Form::select('orderid', $poheadList_hxold, null, ['class' => 'form-control', 'placeholder' => '--请选择--']) !!}
            @elseif ($report->name == "so_amount_statistics")
                {!! Form::select('dateyear', $poheadOrderDateyearList_hxold, null, ['class' => 'form-control', 'placeholder' => '--年份--']) !!}
            @elseif ($report->name == "so_projectengineeringlist_statistics")
                {!! Form::select('orderid', $myprojectListByProjectengineer, null, ['class' => 'form-control', 'placeholder' => '--项目--']) !!}
            @elseif ($report->name == "so_amountstatistics_forfinancedept")
                {!! Form::select('dateyear', $soheadOrderDateyearList_hxold, null, ['class' => 'form-control', 'placeholder' => '--年份--']) !!}
            @elseif ($report->name == "po_statistics_byproject")
                {!! Form::select('project_id', $projectList, null, ['class' => 'form-control', 'placeholder' => '--项目--']) !!}
            @endif

            {!! Form::submit('查找', ['class' => 'btn btn-default btn-sm']) !!}
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
                    @foreach($item as  $value)
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
                        @foreach($item as  $value)
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
                        @foreach($item as  $value)
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
