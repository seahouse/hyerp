@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-sign-in"></i>Login
                                </button>

                                <a href="{{ config('custom.dingtalk.oapi_host') . '/connect/qrconnect?appid=' . config('custom.dingtalk.appid') .'&response_type=code&scope=snsapi_login&state=STATE&redirect_uri=' . url('/') . '/mddauth' }}" class="btn btn-default">钉钉扫码登录</a>

                                @if (config('custom.dingtalk.google2faloginenable') == '1')
                                <a href="{{ url('google2fa/login')  }}" class="btn btn-default">Google Authentication Login</a>
                                @endif

                                <a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your Password?</a>

                            </div>
                        </div>
                    </form>
                    <div id="login_container"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script src="//g.alicdn.com/dingding/dinglogin/0.0.5/ddLogin.js"></script>
    <script>
        var obj = DDLogin({
            id:"login_container",//这里需要你在自己的页面定义一个HTML标签并设置id，例如<div id="login_container"></div>或<span id="login_container"></span>
            goto: "{{ $redirect_uri }}",
            style: "border:none;background-color:#FFFFFF;",
            width : "365",
            height: "400"
        });

        var hanndleMessage = function (event) {
            var origin = event.origin;
            console.log("origin", event.origin);
            if( origin == "https://login.dingtalk.com" ) { //判断是否来自ddLogin扫码事件。
                var loginTmpCode = event.data; //拿到loginTmpCode后就可以在这里构造跳转链接进行跳转了
                console.log("loginTmpCode", loginTmpCode);
                location.href = "https://oapi.dingtalk.com/connect/oauth2/sns_authorize?appid={{ config('custom.dingtalk.appid') }}&response_type=code&scope=snsapi_login&state=STATE&redirect_uri={{ url('/mddauth') }}&loginTmpCode=" + loginTmpCode;
            }

        };

        if (typeof window.addEventListener != 'undefined') {
            window.addEventListener('message', hanndleMessage, false);
        } else if (typeof window.attachEvent != 'undefined') {
            window.attachEvent('onmessage', hanndleMessage);
        }
    </script>
@endsection