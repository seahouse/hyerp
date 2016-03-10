@extends('layouts.app')

@section('title')
    @parent - @yield('title')
@stop

@section('content')
<div class="container">
    <div class="panel panel-info">
        @yield('main')
    </div>
</div>
@stop
