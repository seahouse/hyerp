<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Page Title</title>
	<link href="{{ asset("//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css") }}" rel="stylesheet">
</head>
<body>
	@yield('main')
	<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>   
</body>
	@yield('script')
</html>