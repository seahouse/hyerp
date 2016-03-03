@extends('navbarerp')

@section('main')
    <h1>编辑</h1>
    <hr/>
    
    {!! Form::model($addr, ['method' => 'PATCH', 'action' => ['Addr\AddrsController@update', $addr->id]]) !!}
        @include('addr.addrs._form', ['submitButtonText' => '保存'])
    {!! Form::close() !!}
    
    @include('errors.list')
@stop

@section('script')
    @include('addr.addrs._form_js', [])
@stop
