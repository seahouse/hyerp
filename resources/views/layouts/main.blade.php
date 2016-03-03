<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>发现Laravel 5之美</title>
        <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.4/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/main.css">
    </head>
    
    <body>
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand hidden-sm" href="/">Laravel新手上路</a>
                </div>
                <ul class="nav navbar-nav navbar-right hidden-sm">
                    <a href="{{ url('/auth/register') }}">注册</a>
                    <a href="{{ url('/auth/login') }}">登陆</a>
                </ul>
            </div>
        </div>
        
        <div class="container">
            @yield('content')
        </div>
    </body>
</html>
