@extends('navbarerp')

@section('main')
    <h1>编辑</h1>
    <hr/>
    
    {!! Form::model($report, ['method' => 'PATCH', 'action' => ['System\ReportController@update', $report->id], 'class' => 'form-horizontal']) !!}
        @include('system.report._form', ['submitButtonText' => '保存'])
    {!! Form::close() !!}
    
    @include('errors.list')
@stop

@section('script')
    @include('addr.addrs._form_js', [])
@stop
