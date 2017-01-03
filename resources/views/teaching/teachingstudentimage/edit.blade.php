@extends('navbarerp')

@section('main')
    <h1>编辑</h1>
    <hr/>
    
    {!! Form::model($teachingstudentimage, ['method' => 'PATCH', 'action' => ['Teaching\TeachingstudentimageController@update', $teachingstudentimage->id], 'class' => 'form-horizontal']) !!}
        @include('teaching.teachingstudentimage._form', ['submitButtonText' => '保存'])
    {!! Form::close() !!}
    
 {{--     <ul class="nav nav-tabs">
        {{ URL('items/edit_charass') }}
        <li role="presentation" class="active"><a href="/items/edit_charass">Home</a></li>
        <li role="presentation"><a href="#">Profile</a></li>
        <li role="presentation"><a href="#">Messages</a></li>
    </ul> --}}
    
    {!! Html::image($teachingstudentimage->path, '', array('width' => '300', 'height' => '200')) !!}
    
    @include('errors.list')
@endsection


@section('script')


@endsection
