<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@section('title') - {{ config('custom.companyname') }} @show</title>

    <!-- Fonts -->
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'> -->
    <link href="{{ asset("fonts/googleapis.css") }}" rel='stylesheet' type='text/css'>
    <!-- <link href="{{ asset("https://fonts.googleapis.com/css?family=Lato:100,300,400,700") }}" rel='stylesheet' type='text/css'> -->
    <!-- <link href="{{ asset("http://fonts.useso.com/css?family=Lato:100,300,400,700") }}" rel='stylesheet' type='text/css'> -->

    <!-- Styles -->
    <link href="{{ asset("css/bootstrap.min.css") }}" rel="stylesheet">
    {{--<link href="//cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">--}}
    <!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet"> -->
    <!-- <link href="{{ asset("css/bootstrap.min.css") }}" rel="stylesheet"> -->
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('DataTables/datatables.min.css') }}" />
    {{--<link rel="stylesheet" type="text/css" href="{{ asset('DataTables/DataTables-1.10.16/css/jquery.dataTables.css') }}" />--}}

    <style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }
    </style>
</head>
<body id="app-layout">
    @if (!str_contains(Agent::getUserAgent(), 'DingTalk'))
        @include('layouts.nav')
    @endif

    @yield('content')

    <!-- JavaScripts -->
    <script src="/js/jquery.min.js"></script>
    <!-- <script src="//cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script> -->
    <script src="/js/bootstrap.min.js"></script>
    <!-- <script src="//cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script> -->
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> -->
    
    
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}

    @yield('script')
</body>
</html>
