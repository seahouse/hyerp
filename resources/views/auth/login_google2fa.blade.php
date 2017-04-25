@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/google2fa/login') }}">
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

                        <div class="form-group{{ $errors->has('google2fa_secret') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Secret</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="google2fa_secret">

                                @if ($errors->has('google2fa_secret'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('google2fa_secret') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-sign-in"></i>Login
                                </button>
{{--
                                <a href="{{ config('custom.dingtalk.oapi_host') . '/connect/qrconnect?appid=' . config('custom.dingtalk.appid') .'&response_type=code&scope=snsapi_login&state=STATE&redirect_uri=' . url('/') . '/mddauth' }}" class="btn btn-default">钉钉扫码登录</a>

                                <a href="{{ url('google2fa/login')  }}" class="btn btn-default">Google Authentication Login</a>
--}}

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
