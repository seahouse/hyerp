@extends('navbarerp')

@section('main')
    <h1>导入投标项目</h1>
    <hr/>
    
    {!! Form::open(['url' => 'basic/biddinginformations/importstore', 'class' => 'form-horizontal', 'files' => true]) !!}

    {{--<div class="form-group">--}}
        {{--{!! Form::label('salary_date', '工资日期:', ['class' => 'col-xs-4 col-sm-2 control-label']) !!}--}}
        {{--<div class='col-xs-8 col-sm-10'>--}}
            {{--{!! Form::date('salary_date', date('Y-m-d'), ['class' => 'form-control']) !!}--}}
        {{--</div>--}}
    {{--</div>--}}

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
