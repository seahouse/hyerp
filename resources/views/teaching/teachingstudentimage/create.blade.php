@extends('navbarerp')

@section('main')
    <h2>教学点 -- 添加教学管理员</h2>
    <hr/>
    
    {!! Form::open(array('url' => 'teaching/teachingstudentimage', 'class' => 'form-horizontal', 'files' => true)) !!}
        @include('teaching.teachingstudentimage._form', 
        	[
        		'submitButtonText' => '添加',
        		'attr' => ''
        	]
        )
    {!! Form::close() !!}
    
    @include('errors.list')
@stop
    

