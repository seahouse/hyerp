@extends('navbarerp')

@section('main')
    <h1>编辑</h1>
    <hr/>
    
    {!! Form::model($dept, ['method' => 'PATCH', 'action' => ['System\DeptsController@update', $dept->id]]) !!}
        @include('system.depts._form', ['submitButtonText' => '保存'])
    {!! Form::close() !!}
    
    @include('errors.list')
@stop

@section('script')
    @include('addr.addrs._form_js', [])
@stop
