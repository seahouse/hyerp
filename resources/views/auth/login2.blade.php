@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <ul id="myTab" class="nav nav-tabs">
                <li class="{{ old('tab_active')==''? 'active' : '' }}">
                    <a href="#bypassword" data-toggle="tab">密码登录</a>
                </li>
                <li class="{{ old('tab_active')=='1'? 'active' : '' }}">
                    <a href="#bymessage" data-toggle="tab">短信登录</a>
                </li>
                <li>
                    <a href="#byqr" data-toggle="tab">二维码登录</a>
                </li>
            </ul>
            <div id="myTabContent" class="tab-content">
                <div class="tab-pane fade {{ old('tab_active')==''? 'active in' : '' }}" id="bypassword">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}" style="margin-top: 10px;">
                        {!! csrf_field() !!}
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="col-md-2 control-label" for="email">邮箱</label>

                            <div class="col-md-8">
                                <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}" placeholder="请输入邮箱">

                                @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="col-md-2 control-label" for="password">密码</label>

                            <div class="col-md-8">
                                <input type="password" class="form-control" name="password" id="password" placeholder="请输入密码">

                                @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-2">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> 记住用户名
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-sign-in"></i>登录
                                </button>

                                <a href="{{ config('custom.dingtalk.oapi_host') . '/connect/qrconnect?appid=' . config('custom.dingtalk.appid') .'&response_type=code&scope=snsapi_login&state=STATE&redirect_uri=' . url('/') . '/mddauth' }}" class="btn btn-default">钉钉扫码登录</a>

                                @if (config('custom.dingtalk.google2faloginenable') == '1')
                                <a href="{{ url('google2fa/login')  }}" class="btn btn-default">Google Authentication Login</a>
                                @endif

                                <a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your Password?</a>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="tab-pane fade {{ old('tab_active')=='1'? 'active in' : '' }}" id="bymessage">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/loginbysms') }}" style="margin-top: 10px;">
                        {!! csrf_field() !!}
                        <div class="form-group{{ $errors->has('phonenum') ? ' has-error' : '' }}">
                            <label class="col-md-2 control-label" for="phonenum">手机号</label>

                            <div class="col-md-8">
                                <input type="text" class="form-control" name="phonenum" id="phonenum" value="{{ old('phonenum') }}" placeholder="请输入手机号" required pattern="^(13[0-9]|14[5-9]|15[0-3,5-9]|16[2,5,6,7]|17[0-8]|18[0-9]|19[0-3,5-9])\d{8}$">

                                @if ($errors->has('phonenum'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('phonenum') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                            <label class="col-md-2 control-label" for="code">验证码</label>

                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="code" id="code" placeholder="请输入验证码" required pattern="^\d{4}$">

                                    <span class="input-group-btn">
                                        <input class="btn btn-default" type="button" id="btnSend" value="发送验证码">
                                    </span>

                                    <input type="hidden" id="syscode" name="syscode" value="{{ old('syscode') }}">
                                </div>

                                @if ($errors->has('code'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('code') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-sign-in"></i>登录
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="tab-pane fade" id="byqr">
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
        id: "login_container", //这里需要你在自己的页面定义一个HTML标签并设置id，例如<div id="login_container"></div>或<span id="login_container"></span>
        goto: "{{ $redirect_uri }}",
        style: "border:none;background-color:#FFFFFF;",
        width: "365",
        height: "400"
    });

    var hanndleMessage = function(event) {
        var origin = event.origin;
        console.log("origin", event.origin);
        if (origin == "https://login.dingtalk.com") { //判断是否来自ddLogin扫码事件。
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

    //发送验证码时添加cookie
    function addCookie(name, value, expiresHours) {
        var cookieString = name + "=" + escape(value);
        //判断是否设置过期时间,0代表关闭浏览器时失效
        if (expiresHours > 0) {
            var date = new Date();
            date.setTime(date.getTime() + expiresHours * 1000);
            cookieString = cookieString + ";expires=" + date.toUTCString();
        }
        document.cookie = cookieString;
    }
    //修改cookie的值
    function editCookie(name, value, expiresHours) {
        var cookieString = name + "=" + escape(value);
        if (expiresHours > 0) {
            var date = new Date();
            date.setTime(date.getTime() + expiresHours * 1000); //单位是毫秒
            cookieString = cookieString + ";expires=" + date.toGMTString();
        }
        document.cookie = cookieString;
    }
    //根据名字获取cookie的值
    function getCookieValue(name) {
        var strCookie = document.cookie;
        var arrCookie = strCookie.split("; ");
        for (var i = 0; i < arrCookie.length; i++) {
            var arr = arrCookie[i].split("=");
            if (arr[0] == name) {
                return unescape(arr[1]);
            } else {
                continue;
            }
        }
        return "";
    }

    $(function() {
        $("#btnSend").click(function() {
            sendCode($("#btnSend"));
        });
        v = getCookieValue("secondsremained"); //获取cookie值
        if (v > 0) {
            settime($("#btnSend")); //开始倒计时
        }
    })

    //发送验证码
    function sendCode(obj) {
        var phonenum = $("#phonenum").val();
        var result = isPhoneNum();
        if (result) {
            doPostBack('sendsmscode', {
                "phonenum": phonenum,
                "_token": "{{ csrf_token() }}",
            });
            addCookie("secondsremained", 60, 60); //添加cookie记录,有效时间60s
            settime(obj); //开始倒计时
        }
    }

    //将手机利用ajax提交到后台的发短信接口
    function doPostBack(url, queryParam) {
        $.ajax({
            async: false,
            cache: false,
            type: 'POST',
            url: url,
            data: queryParam,
            error: function(data) {
                console.log(data);
            },
            success: function(data) {
                console.log(data);

                if (data.Code == "OK") {
                    $("#syscode").val(data.vcode);
                } else {
                    alert(data.Message);
                }
            }
        });
    }

    //开始倒计时
    var countdown;

    function settime(obj) {
        countdown = getCookieValue("secondsremained");
        if (countdown == 0) {
            obj.removeAttr("disabled");
            obj.val("发送验证码");
            return;
        } else {
            obj.attr("disabled", true);
            obj.val("重新发送(" + countdown + ")");
            countdown--;
            editCookie("secondsremained", countdown, countdown + 1);
        }
        setTimeout(function() {
            settime(obj)
        }, 1000) //每1000毫秒执行一次
    }

    //校验手机号是否合法
    function isPhoneNum() {
        var phonenum = $("#phonenum").val();
        var myreg = /^(13[0-9]|14[5-9]|15[0-3,5-9]|16[2,5,6,7]|17[0-8]|18[0-9]|19[0-3,5-9])\d{8}$/;
        if (!myreg.test(phonenum)) {
            alert('请输入有效的手机号码！');
            return false;
        } else {
            return true;
        }
    }
</script>
@endsection