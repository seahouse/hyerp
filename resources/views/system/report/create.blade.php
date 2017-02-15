@extends('navbarerp')

@section('main')
    <h1>添加报表</h1>
    <hr/>
    
    {!! Form::open(['url' => 'system/report', 'class' => 'form-horizontal']) !!}
        @include('system.report._form', ['submitButtonText' => '添加'])
    {!! Form::close() !!}
    
    
@stop