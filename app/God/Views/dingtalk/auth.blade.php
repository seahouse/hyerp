<!DOCTYPE html>
<html>
<head>
    <title>{{ $G_trans('dingtalk.auth.title') }}</title>
    <script src="https://g.alicdn.com/ilw/ding/0.7.5/scripts/dingtalk.js"></script>
</head>
<body>
    <p class="text-center"><h1>{{ $G_trans('dingtalk.auth.message') }}<h1></p>
    <p style="color:white">
    @foreach ($ddconfig as $key => $value)
        <input type="hidden" id="{{$key}}" name="{{$key}}" value="{{$value}}">
        {{$key}}: {{$value}}<br>
    @endforeach
    </p>

    <script>
        var _config = {
            agentId: document.getElementById("agentid").value,
            corpId: document.getElementById("corpid").value,
            timeStamp: document.getElementById("timestamp").value,
            nonceStr: document.getElementById("noncestr").value,
            signature: document.getElementById("signature").value,
        }

        dd.config({
            agentId: _config.agentId,
            corpId: _config.corpId,
            timeStamp: _config.timeStamp,
            nonceStr: _config.nonceStr,
            signature: _config.signature,
            jsApiList: ['runtime.info',
                'biz.contact.choose',
                'device.notification.confirm',
                'device.notification.alert',
                'device.notification.prompt',
                'biz.ding.post']
        });

        dd.ready(function() {
            dd.runtime.info({
                onSuccess: function(info) {
                    // TODO: Check info.ability
                },
                onFail: function(err) {
                    window.location.replace(window.location.href + '&error=dd.runtime.info.onFail:' + JSON.stringify(err));
                }
            });

            dd.runtime.permission.requestAuthCode({
                corpId: _config.corpId,
                onSuccess: function (info) {
                    window.location.replace(window.location.href + '&code=' + info.code);
                },
                onFail: function (err) {
                    window.location.replace(window.location.href + '&error=dd.runtime.permission.requestAuthCode.onFail:' + JSON.stringify(err));
                }
            });
        });

        dd.error(function(err) {
            window.location.replace(window.location.href + '&error=dd.error:' + JSON.stringify(err));
        });
    </script>
</body>
</html>