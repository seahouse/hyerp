@extends('navbarerp')

@section('main')
    <h1>编辑(Edit)</h1>
    <hr/>
    
    {!! Form::model($biddingproject, ['method' => 'PATCH', 'action' => ['Basic\BiddingprojectController@update', $biddingproject->id], 'class' => 'form-horizontal']) !!}
        @include('basic.biddingprojects._form', ['submitButtonText' => '保存(Save)','attr' => '','btnclass' => 'btn btn-primary',])
    {!! Form::close() !!}
    
    @include('errors.list')
@stop

