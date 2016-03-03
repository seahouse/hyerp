@extends('navbarerp')

@section('main')
    <h1>编辑</h1>
    <hr/>
    
{{--    {!! Image::url('uploads/photo.jpg',300,300,array('crop','grayscale')); !!}
    <img src="{!! Storage::get('images/2015082403090912622.jpg') !!}" />
    <img src="{!! $image->path !!}" />
    <img src="E:/myproject/la514test/trunk/storage/app/images/2015082403090912622.jpg" /> --}}
        
    
    {!! Form::model($image, ['method' => 'PATCH', 'action' => ['System\ImagesController@update', $image->id], 'files' => true]) !!}
        @include('system.images._form', ['submitButtonText' => '保存'])
    {!! Form::close() !!}
    
    {!! Html::image($image->path, '', array('width' => '300', 'height' => '200')) !!}
    
    @include('errors.list')
@stop

