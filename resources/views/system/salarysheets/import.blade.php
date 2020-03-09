@extends('navbarerp')

@section('head')
    <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }} ">
@endsection

@section('main')
    <h1>导入工资表</h1>
    <hr/>
    
    {!! Form::open(['url' => 'system/salarysheet/importstore', 'class' => 'form-horizontal', 'files' => true]) !!}

    {{--https://www.bootcss.com/p/bootstrap-datetimepicker/--}}
    {{--https://blog.csdn.net/u011628981/article/details/70213741--}}
    <div class="form-group">
        {!! Form::label('salary_date', '工资月份:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
        <div class='col-xs-8 col-sm-10'>
            {{--{!! Form::date('salary_date', date('Y-m-d'), ['class' => 'form-control']) !!}--}}
            <input name="salary_date" type="text" value="{{ date('Y-m') }}" id="salary_date" class="form-control">
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('file', '选择Excel文件:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}
        <div class='col-xs-8 col-sm-10'>
            <div class="row">
                {!! Form::file('file', []) !!}
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                {!! Form::submit('导入', ['class' => 'btn btn-primary', 'id' => 'btnSubmit']) !!}
            </div>
        </div>
    </div>
    {!! Form::close() !!}
    
    @include('errors.list')
@endsection

@section('script')
    <script type="text/javascript" src="/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function(e) {
            $('#salary_date').datetimepicker({
                // https://www.bootcss.com/p/bootstrap-datetimepicker
                // https://blog.csdn.net/u011628981/article/details/70213741
                format: 'yyyy-mm',
                autoclose: true,
                startView: 3,
                minView: 3,
                language:"zh-CN",
//                format: 'yyyy-mm-dd hh:ii'
            });
        });
    </script>
@endsection
