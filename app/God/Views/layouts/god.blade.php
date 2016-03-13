@extends('layouts.app')

@if (request()->has('title'))
@section('title') {{request()->get('title')}} @stop
@elseif (isset($viewTitle))
@section('title') {{$viewTitle}} @stop
@else
@section('title') {{$G_viewTitle}} @stop
@endif

@section('content')
<div class="container">
    @if (session('god.log'))
        <div class="alert alert-info alert-dismissable fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            {{ session('god.log') }}
        </div>
    @endif

    @yield('god.content')
</div>
@stop
