<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>@yield('title')</title>
<!--	<link href="{{ asset("//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css") }}" rel="stylesheet"> -->
	<link href="{{ asset("css/bootstrap.min.css") }}" rel="stylesheet">
	<link href="{{ asset("fonts/iconfont.css") }}" rel="stylesheet">
	<link href="{{ asset("css/styles.css?v=20170713") }}" rel="stylesheet">
</head>
<body>
	@include('_back')
	@yield('main')
	<script src="/js/jquery.min.js"></script>
	<!-- <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script> -->
	<script src="/js/bootstrap.min.js"></script>
    <!-- <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script> -->
    <!-- <script src="js/jquery.min.js"></script> -->
    <!-- <script src="js/bootstrap.min.js"></script> -->
</body>
	@yield('script')
</html>