@extends('navbarerp')

@section('main')
    <h1>添加部门</h1>
    <hr/>
    
    {!! Form::open(['url' => 'system/depts']) !!}
        @include('system.depts._form', ['submitButtonText' => '添加'])
    {!! Form::close() !!}
    
    
@stop