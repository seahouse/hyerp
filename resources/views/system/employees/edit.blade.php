@extends('navbarerp')

@section('main')
    <h1>编辑</h1>
    <hr/>
    
    {!! Form::model($employee, ['method' => 'PATCH', 'action' => ['System\EmployeesController@update', $employee->id]]) !!}
        @include('system.employees._form', ['submitButtonText' => '保存'])
    {!! Form::close() !!}
    
    @include('errors.list')
@stop

@section('script')
    @include('addr.addrs._form_js', [])
@stop
