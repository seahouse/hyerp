@extends('navbarerp')

@section('main')
    <h2>投标项目 -- 添加字段</h2>
    <hr/>
    
    {!! Form::open(array('url' => 'basic/biddinginformationdefinefields', 'class' => 'form-horizontal')) !!}
        @include('basic.biddinginformationdefinefields._form',
            [
                'submitButtonText' => '添加',
                'attr' => null,
            ]
        )
    {!! Form::close() !!}
    
    @include('errors.list')
@stop
    

