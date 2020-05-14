@extends('navbarerp')

@section('main')
    <h2>施工标字段 -- 添加字段</h2>
    <hr/>
    
    {!! Form::open(array('url' => 'basic/constructionbidinformationfields', 'class' => 'form-horizontal')) !!}
        @include('basic.constructionbidinformationfields._form',
            [
                'submitButtonText' => '添加',
                'attr' => null,
            ]
        )
    {!! Form::close() !!}
    
    @include('errors.list')
@stop
    

