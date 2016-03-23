@extends('navbarerp')

@section('main')
    <h2>审批 -- 添加设置</h2>
    <hr/>
    
    {!! Form::open(array('url' => 'approval/approversettings', 'class' => 'form-horizontal')) !!}
        @include('approval.approversettings._form', ['submitButtonText' => '添加', 'marketprice' => '0.0'])
    {!! Form::close() !!}
    
    @include('errors.list')
@stop
    

