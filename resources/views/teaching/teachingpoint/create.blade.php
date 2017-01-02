@extends('navbarerp')

@section('main')
    <h2>教学点 -- 添加教学点</h2>
    <hr/>
    
    {!! Form::open(array('url' => 'teaching/teachingpoint', 'class' => 'form-horizontal')) !!}
        @include('teaching.teachingpoint._form', 
        	[
        		'submitButtonText' => '添加',
        		'attr' => ''
        	]
        )
    {!! Form::close() !!}
    
    @include('errors.list')
@stop
    

