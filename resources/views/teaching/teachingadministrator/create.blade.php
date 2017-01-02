@extends('navbarerp')

@section('main')
    <h2>教学点 -- 添加教学管理员</h2>
    <hr/>
    
    {!! Form::open(array('url' => 'teaching/teachingadministrator', 'class' => 'form-horizontal')) !!}
        @include('teaching.teachingadministrator._form', 
        	[
        		'submitButtonText' => '添加',
        		'attr' => ''
        	]
        )
    {!! Form::close() !!}
    
    @include('errors.list')
@stop
    

