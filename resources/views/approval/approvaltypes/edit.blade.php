@extends('navbarerp')

@section('main')
    <h1>编辑</h1>
    <hr/>
    
    {!! Form::model($approvaltype, ['method' => 'PATCH', 'action' => ['Approval\ApprovaltypesController@update', $approvaltype->id], 'class' => 'form-horizontal']) !!}
        @include('approval.approvaltypes._form', ['submitButtonText' => '保存'])
    {!! Form::close() !!}  



    @include('errors.list')
@endsection

