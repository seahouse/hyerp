@extends('navbarerp')

@section('title', '工资条')

@section('main')
    <div class="panel-body">
        {{--{!! Form::open(['url' => '/shipment/salarysheet/export', 'class' => 'pull-right']) !!}--}}
            {{--{!! Form::submit('Export', ['class' => 'btn btn-default btn-sm']) !!}--}}
        {{--{!! Form::close() !!}--}}

        @if ($salarysheets->count())
            <table class="table table-striped table-hover table-condensed">
                <thead>
                <tr>
                    <th>工资月份</th>
                    <th>姓名</th>
                    <th>部门</th>
                    <th>实发工资</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($salarysheets as $salarysheet)
                    <tr>
                        <td>
                            {{ \Carbon\Carbon::parse($salarysheet->salary_date)->format('Y-m') }}
                        </td>
                        <td>
                            {{ $salarysheet->username }}
                        </td>
                        <td>
                            {{ $salarysheet->department }}
                        </td>
                        <td>
                            {{ $salarysheet->actualsalary_amount }}
                        </td>
                        <td>
                            <a href="{{ URL::to('/system/salarysheet/'.$salarysheet->id.'/mshow') }}" class="btn btn-success btn-sm pull-left">详细信息</a>
                            {{--<a href="{{ URL::to('/shipment/shipments/'.$salarysheet->id.'/export') }}" class="btn btn-success btn-sm pull-left">导出</a>--}}
                            {{--{!! Form::open(array('route' => array('system.salarysheet.destroy', $salarysheet->id), 'method' => 'delete', 'onsubmit' => 'return confirm("确定删除此记录?");')) !!}--}}
                            {{--{!! Form::submit('删除', ['class' => 'btn btn-danger btn-sm']) !!}--}}
                            {{--{!! Form::close() !!}--}}
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>
            {{--{!! $salarysheets->render() !!}--}}
{{--            {!! $salarysheets->setPath('/system/salarysheet')->appends($inputs)->links() !!}--}}
        @else
            <div class="alert alert-warning alert-block">
                <i class="fa fa-warning"></i>
                {{'无记录(No Record)', [], 'layouts'}}
            </div>
        @endif
    </div>
@endsection

