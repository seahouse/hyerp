@extends('navbarerp')

@section('main')
    <h2>审批 -- 添加设置</h2>
    <hr/>
    
    {!! Form::open(array('url' => 'approval/approvaltypes', 'class' => 'form-horizontal')) !!}
        @include('approval.approvaltypes._form', ['submitButtonText' => '添加'])
    {!! Form::close() !!}
    
    @include('errors.list')
@stop
    

