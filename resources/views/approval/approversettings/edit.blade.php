@extends('navbarerp')

@section('main')
    <h1>编辑</h1>
    <hr/>
    
    {!! Form::model($approversetting, ['method' => 'PATCH', 'action' => ['Approval\ApproversettingsController@update', $approversetting->id], 'class' => 'form-horizontal']) !!}
        @include('approval.approversettings._form', ['submitButtonText' => '保存'])
    {!! Form::close() !!}  



    @include('errors.list')
@endsection

